<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\CartItem;
use App\Models\Notification;
use App\Models\DailyLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class CustomerCheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Keranjang belanja Anda kosong. Silakan tambahkan produk terlebih dahulu.');
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->qty * $item->product->price;
        });

        $googleMapsApiKey = config('services.google_maps.api_key');

        return view('customer.checkout.index', compact('cartItems', 'totalPrice', 'user', 'googleMapsApiKey'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'customer_notes' => 'nullable|string|max:500',
            'customer_lat' => 'nullable|numeric',
            'customer_lng' => 'nullable|numeric',
            'payment_method' => 'required|in:midtrans,cash',
        ]);

        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->qty * $item->product->price;
        });

        // -----------------------------------------------------
        // Check Daily Order Limit before proceeding
        // -----------------------------------------------------
        $dailyLimit = DailyLimit::first();
        
        if ($dailyLimit && $dailyLimit->is_active) {
            $today = \Carbon\Carbon::today('Asia/Jakarta')->format('Y-m-d');
            
            // Count today's orders that are not cancelled or expired
            // Note: withTrashed() is optional depending on if soft deletes should count, 
            // usually we don't want to count cancelled ones.
            $todayOrderCount = Order::whereDate('order_date', $today)
                ->whereNotIn('status', ['cancelled', 'expired'])
                ->count();
                
            if ($todayOrderCount >= $dailyLimit->max_orders_per_day) {
                return redirect()->route('customer.cart.index')->with('error', 'Mohon maaf, batas maksimal pesanan harian toko telah tercapai. Silakan coba kembali besok.');
            }
        }

        DB::beginTransaction();

        try {
            // 1. Create the Order
            $order = Order::create([
                'user_id' => $user->id,
                'invoice_number' => Order::generateInvoice(),
                'total_price' => $totalPrice,
                'payment_status' => 'pending',
                'order_status' => $request->payment_method === 'cash' ? 'diproses' : 'menunggu_pembayaran',
                'payment_method' => $request->payment_method,
                'customer_notes' => $request->customer_notes,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'customer_lat' => $request->customer_lat,
                'customer_lng' => $request->customer_lng,
                'order_date' => now()->toDateString(),
            ]);

            // 2. Create Order Details
            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                    'price' => $item->product->price,
                    'subtotal' => $item->qty * $item->product->price,
                    'notes' => $item->notes,
                ]);
            }

            // 3. Clear Cart
            CartItem::where('user_id', $user->id)->delete();

            if ($request->payment_method === 'midtrans') {
                // 4a. Configure Midtrans Snap
                \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
                \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
                \Midtrans\Config::$isSanitized = true;
                \Midtrans\Config::$is3ds = true;

                // Prepare transaction payload
                $transaction_details = [
                    'order_id' => $order->invoice_number,
                    'gross_amount' => (int) $order->total_price,
                ];

                $item_details = [];
                foreach ($order->details()->with('product')->get() as $detail) {
                    $item_details[] = [
                        'id' => (string) $detail->product_id,
                        'price' => (int) $detail->price,
                        'quantity' => $detail->qty,
                        'name' => Str::limit($detail->product->name, 50),
                    ];
                }

                $customer_details = [
                    'first_name' => $order->customer_name,
                    'phone' => $order->customer_phone,
                    'email' => $user->email,
                ];

                $payload = [
                    'transaction_details' => $transaction_details,
                    'item_details' => $item_details,
                    'customer_details' => $customer_details,
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($payload);

                // 5a. Create Payment record with snap token
                Payment::create([
                    'order_id' => $order->id,
                    'payment_status' => 'pending',
                    'payment_method' => 'midtrans',
                    'snap_token' => $snapToken,
                ]);
            } else {
                // 4b. Cash payment — no Midtrans needed
                Payment::create([
                    'order_id' => $order->id,
                    'payment_status' => 'pending',
                    'payment_method' => 'cash',
                ]);

                // Send notification for cash order
                Notification::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'title' => 'Pesanan Cash Berhasil Dibuat! 🛒',
                    'message' => 'Pesanan ' . $order->invoice_number . ' dengan pembayaran Cash telah dibuat. Silakan siapkan uang tunai saat pesanan diantar.',
                    'type' => 'info',
                    'is_read' => false,
                ]);
            }

            DB::commit();

            $successMsg = $request->payment_method === 'cash'
                ? 'Pesanan berhasil dibuat! Pembayaran akan dilakukan secara tunai saat pengantaran.'
                : 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.';

            return redirect()->route('customer.orders.show', $order)->with('success', $successMsg);

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat checkout: ' . $e->getMessage())->withInput();
        }
    }
}
