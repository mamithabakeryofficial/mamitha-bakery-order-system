<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Exception;

class PaymentNotificationController extends Controller
{
    public function handle(Request $request)
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

        try {
            $notification = new \Midtrans\Notification();
            
            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraud = $notification->fraud_status;

            // Find our order (including softly deleted ones in case of delayed payment notification)
            $order = Order::withTrashed()->where('invoice_number', $orderId)->first();
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $payment = Payment::where('order_id', $order->id)->first();

            $payment_status = 'pending';
            $order_status = 'menunggu_pembayaran';

            // Map Midtrans status to local DB status
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $payment_status = 'pending';
                        $order_status = 'menunggu_pembayaran';
                    } else {
                        $payment_status = 'settlement';
                        $order_status = 'dibayar';
                    }
                }
            } else if ($transaction == 'settlement') {
                $payment_status = 'settlement';
                $order_status = 'dibayar';
            } else if ($transaction == 'pending') {
                $payment_status = 'pending';
                $order_status = 'menunggu_pembayaran';
            } else if ($transaction == 'deny') {
                $payment_status = 'deny';
                $order_status = 'dibatalkan';
            } else if ($transaction == 'expire') {
                $payment_status = 'expire';
                $order_status = 'dibatalkan';
            } else if ($transaction == 'cancel') {
                $payment_status = 'cancel';
                $order_status = 'dibatalkan';
            }

            // Update order
            $order->update([
                'order_status' => $order_status,
                'payment_status' => $payment_status,
            ]);

            // Update or create payment
            if ($payment) {
                $payment->update([
                    'payment_status' => $payment_status,
                    'payment_method' => $type,
                    'midtrans_transaction_id' => $notification->transaction_id,
                    'raw_response' => json_encode($notification),
                    'paid_at' => $payment_status == 'settlement' ? now() : null,
                ]);
            } else {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_status' => $payment_status,
                    'payment_method' => $type,
                    'midtrans_transaction_id' => $notification->transaction_id,
                    'raw_response' => json_encode($notification),
                    'paid_at' => $payment_status == 'settlement' ? now() : null,
                ]);
            }

            // Send local notification if status changes to success/fail
            if ($payment_status == 'settlement') {
                Notification::create([
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'title' => 'Pembayaran Berhasil! 🎉',
                    'message' => 'Pembayaran untuk pesanan ' . $order->invoice_number . ' telah lunas. Pesanan Anda akan segera diproses.',
                    'type' => 'success',
                    'is_read' => false,
                ]);
            } elseif (in_array($payment_status, ['deny', 'expire', 'cancel'])) {
                Notification::create([
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'title' => 'Pesanan Dibatalkan ❌',
                    'message' => 'Pesanan ' . $order->invoice_number . ' telah dibatalkan karena pembayaran bermasalah atau kedaluwarsa.',
                    'type' => 'danger',
                    'is_read' => false,
                ]);
            }

            return response()->json(['message' => 'Status updated successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
