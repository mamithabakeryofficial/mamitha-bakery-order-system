<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Notification;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function dashboard()
    {
        $courierPhone = auth()->user()->phone;
        $courierName = auth()->user()->name;

        // Clean user phone number for database suffix check
        $cleanPhone = preg_replace('/[^0-9]/', '', $courierPhone);
        $phoneSuffix = strlen($cleanPhone) >= 9 ? substr($cleanPhone, -9) : $cleanPhone;

        // Fetch orders where courier is assigned and status is 'sedang_dikirim'
        $activeDeliveries = Order::where('order_status', 'sedang_dikirim')
            ->where(function ($query) use ($courierPhone, $courierName, $phoneSuffix) {
                // Match by phone
                $query->where('courier_phone', $courierPhone);
                
                if (!empty($phoneSuffix)) {
                    $query->orWhere('courier_phone', 'like', '%' . $phoneSuffix);
                }

                // Match by name (exact case-insensitive and partial)
                $query->orWhere('courier_name', $courierName)
                      ->orWhereRaw('LOWER(courier_name) = ?', [strtolower($courierName)])
                      ->orWhereRaw('LOWER(courier_name) LIKE ?', ['%' . strtolower($courierName) . '%'])
                      ->orWhere(function($q) use ($courierName) {
                          $q->whereNotNull('courier_name')
                            ->whereRaw('? LIKE CONCAT("%", courier_name, "%")', [$courierName]);
                      });
            })
            ->with(['user', 'details.product'])
            ->latest()
            ->get();

        // Fetch completed orders assigned to this courier
        $completedDeliveries = Order::where('order_status', 'selesai')
            ->where(function ($query) use ($courierPhone, $courierName, $phoneSuffix) {
                // Match by phone
                $query->where('courier_phone', $courierPhone);
                
                if (!empty($phoneSuffix)) {
                    $query->orWhere('courier_phone', 'like', '%' . $phoneSuffix);
                }

                // Match by name (exact case-insensitive and partial)
                $query->orWhere('courier_name', $courierName)
                      ->orWhereRaw('LOWER(courier_name) = ?', [strtolower($courierName)])
                      ->orWhereRaw('LOWER(courier_name) LIKE ?', ['%' . strtolower($courierName) . '%'])
                      ->orWhere(function($q) use ($courierName) {
                          $q->whereNotNull('courier_name')
                            ->whereRaw('? LIKE CONCAT("%", courier_name, "%")', [$courierName]);
                      });
            })
            ->with(['user', 'details.product'])
            ->latest()
            ->take(15)
            ->get();

        return view('courier.dashboard', compact('activeDeliveries', 'completedDeliveries'));
    }

    private function isCourierAssigned($order, $user)
    {
        $userPhone = preg_replace('/[^0-9]/', '', $user->phone);
        $orderPhone = preg_replace('/[^0-9]/', '', $order->courier_phone);

        // Check phone suffix match (last 9 digits)
        if ($userPhone && $orderPhone && strlen($userPhone) >= 9 && strlen($orderPhone) >= 9) {
            if (substr($userPhone, -9) === substr($orderPhone, -9)) {
                return true;
            }
        }

        // Exact match
        if ($order->courier_phone === $user->phone || $order->courier_name === $user->name) {
            return true;
        }

        // Case-insensitive name match
        if (strtolower($order->courier_name) === strtolower($user->name)) {
            return true;
        }

        // Partial name match (order courier name is part of user name, or vice versa)
        if ($order->courier_name && $user->name) {
            $orderNameLower = strtolower($order->courier_name);
            $userNameLower = strtolower($user->name);
            if (str_contains($userNameLower, $orderNameLower) || str_contains($orderNameLower, $userNameLower)) {
                return true;
            }
        }

        return false;
    }

    public function completeDelivery(Order $order)
    {
        $user = auth()->user();

        // Ensure this order belongs to the logged-in courier
        if (!$this->isCourierAssigned($order, $user)) {
            abort(403, 'Anda tidak ditugaskan untuk pesanan ini.');
        }

        // Only allow completing orders that are currently in 'sedang_dikirim' status
        if ($order->order_status !== 'sedang_dikirim') {
            return back()->with('error', 'Pesanan ini tidak berada dalam status pengiriman.');
        }

        // Update order status to selesai
        $order->update([
            'order_status' => 'selesai',
        ]);

        // If payment is Cash/COD, settle the payment on delivery completion
        if ($order->payment_method === 'cash') {
            $order->update([
                'payment_status' => 'settlement',
            ]);

            if ($order->payment) {
                $order->payment->update([
                    'payment_status' => 'settlement',
                    'paid_at' => now(),
                ]);
            }
        }

        // Create customer notification
        Notification::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'title' => 'Pesanan Selesai Diterima! 🎉',
            'message' => 'Pesanan ' . $order->invoice_number . ' telah berhasil diantar oleh kurir. Terima kasih telah berbelanja di Mamitha Bakery!',
            'type' => 'success',
            'is_read' => false,
        ]);

        return back()->with('success', 'Pengiriman pesanan ' . $order->invoice_number . ' berhasil diselesaikan.');
    }
}
