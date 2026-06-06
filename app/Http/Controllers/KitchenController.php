<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function dashboard()
    {
        // Orders that kitchen needs to handle
        $ordersToMake = Order::where('order_status', 'sedang_dibuat')
            ->with(['user', 'details.product'])
            ->latest()
            ->get();

        $ordersWaiting = Order::where('order_status', 'diproses')
            ->with(['user', 'details.product'])
            ->latest()
            ->get();

        $ordersReady = Order::where('order_status', 'siap_diambil')
            ->with(['user', 'details.product'])
            ->latest()
            ->get();

        $completedToday = Order::where('order_status', 'selesai')
            ->whereDate('updated_at', today())
            ->count();

        return view('kitchen.dashboard', compact(
            'ordersToMake',
            'ordersWaiting',
            'ordersReady',
            'completedToday'
        ));
    }

    public function checkNewOrders(Request $request)
    {
        $lastId = $request->get('last_id', 0);
        
        $newOrdersCount = Order::whereIn('order_status', ['diproses', 'sedang_dibuat'])
            ->where('id', '>', $lastId)
            ->count();
            
        $maxId = Order::max('id') ?? 0;

        return response()->json([
            'new_orders_count' => $newOrdersCount,
            'max_id' => $maxId
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:sedang_dibuat,siap_diambil,sedang_dikirim,selesai',
        ]);

        $order->update(['order_status' => $request->order_status]);

        return back()->with('success', 'Status pesanan ' . $order->invoice_number . ' berhasil diupdate!');
    }

    public function print(Order $order)
    {
        $order->load(['user', 'details.product']);
        return view('kitchen.print', compact('order'));
    }
}
