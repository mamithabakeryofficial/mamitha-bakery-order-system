<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->withTrashed();

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                  ->orWhere('customer_name', 'like', "%{$request->search}%");
            });
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'details.product', 'payment']);
        $couriers = User::where('role', 'courier')->get();
        return view('admin.orders.show', compact('order', 'couriers'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:menunggu_pembayaran,dibayar,diproses,sedang_dibuat,siap_diambil,sedang_dikirim,selesai,dibatalkan',
        ]);

        if ($request->order_status === 'sedang_dikirim' && (empty($order->courier_name) || empty($order->courier_phone))) {
            return back()->with('error', 'Gagal mengubah status. Untuk mengubah status menjadi "Sedang Dikirim", silakan gunakan form "Assign Kurir Pengirim" di sebelah kanan.');
        }

        $order->update(['order_status' => $request->order_status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * Confirm cash payment for an order.
     */
    public function confirmCashPayment(Order $order)
    {
        if ($order->payment_method !== 'cash' || $order->payment_status === 'settlement') {
            return back()->with('error', 'Pesanan ini tidak memerlukan konfirmasi pembayaran cash.');
        }

        $order->update([
            'payment_status' => 'settlement',
        ]);

        // Update payment record
        if ($order->payment) {
            $order->payment->update([
                'payment_status' => 'settlement',
                'paid_at' => now(),
            ]);
        }

        // Send notification to customer
        Notification::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'title' => 'Pembayaran Cash Dikonfirmasi! ✅',
            'message' => 'Pembayaran tunai untuk pesanan ' . $order->invoice_number . ' telah dikonfirmasi oleh admin. Terima kasih!',
            'type' => 'success',
            'is_read' => false,
        ]);

        return back()->with('success', 'Pembayaran cash untuk pesanan ' . $order->invoice_number . ' berhasil dikonfirmasi!');
    }

    /**
     * Assign courier and set order status to 'sedang_dikirim'.
     */
    public function assignCourier(Request $request, Order $order)
    {
        $request->validate([
            'courier_name' => 'required|string|max:100',
            'courier_phone' => 'required|string|max:20',
            'delivery_notes' => 'nullable|string|max:500',
        ]);

        $courierName = trim($request->courier_name);
        $courierPhone = trim($request->courier_phone);

        // Normalize phone number to digits only
        $cleanInputPhone = preg_replace('/[^0-9]/', '', $courierPhone);
        
        $courierUser = null;

        // 1. Try to find courier user by exact phone suffix matching (last 9 digits)
        if (strlen($cleanInputPhone) >= 9) {
            $phoneSuffix = substr($cleanInputPhone, -9);
            $courierUser = User::where('role', 'courier')
                ->where('phone', 'like', '%' . $phoneSuffix)
                ->first();
        }

        // 2. If not found, try to find by case-insensitive name match
        if (!$courierUser) {
            $courierUser = User::where('role', 'courier')
                ->where('name', 'like', '%' . $courierName . '%')
                ->first();
        }

        // 3. If not found, try case-insensitive partial search
        if (!$courierUser) {
            $courierUser = User::where('role', 'courier')
                ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($courierName) . '%'])
                ->first();
        }

        // If we matched a registered courier user, overwrite with their database values
        // to guarantee exact match in the CourierController dashboard
        if ($courierUser) {
            $courierName = $courierUser->name;
            $courierPhone = $courierUser->phone;
        }

        $order->update([
            'courier_name' => $courierName,
            'courier_phone' => $courierPhone,
            'delivery_notes' => $request->delivery_notes,
            'order_status' => 'sedang_dikirim',
        ]);

        // Send notification to customer
        Notification::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'title' => 'Pesanan Sedang Dikirim! 🚚',
            'message' => 'Pesanan ' . $order->invoice_number . ' sedang dalam perjalanan menuju alamat Anda. Kurir: ' . $courierName . ' (' . $courierPhone . ')',
            'type' => 'info',
            'is_read' => false,
        ]);

        return back()->with('success', 'Kurir berhasil di-assign! Pesanan ' . $order->invoice_number . ' sedang dikirim oleh ' . $courierName . '.');
    }
}
