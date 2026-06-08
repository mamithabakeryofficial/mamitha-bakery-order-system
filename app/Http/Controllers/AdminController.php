<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Summary stats
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'settlement')->sum('total_price');
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();

        // Orders by status
        $pendingOrders = Order::where('order_status', 'menunggu_pembayaran')->count();
        $processingOrders = Order::where('order_status', 'diproses')->count();
        $completedOrders = Order::where('order_status', 'selesai')->count();
        $cancelledOrders = Order::where('order_status', 'dibatalkan')->count();

        // Recent orders
        $recentOrders = Order::with('user')->latest()->take(10)->get();

        // Daily revenue for the last 7 days
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();
        $dailyRevenue = Order::where('payment_status', 'settlement')
            ->whereBetween('order_date', [$startDate, $endDate])
            ->select(\Illuminate\Support\Facades\DB::raw('DATE(order_date) as date'), \Illuminate\Support\Facades\DB::raw('SUM(total_price) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailyLimit = \App\Models\DailyLimit::firstOrCreate([], [
            'max_orders_per_day' => 100,
            'max_products_per_day' => 500,
            'opening_time' => '08:00',
            'closing_time' => '20:00',
            'is_active' => true,
        ]);

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'totalCustomers',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'cancelledOrders',
            'recentOrders',
            'dailyRevenue',
            'dailyLimit'
        ));
    }

    public function updateDailyLimit(Request $request)
    {
        $request->validate([
            'max_orders_per_day' => 'required|integer|min:0',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'is_active' => 'sometimes|boolean',
        ]);

        $dailyLimit = \App\Models\DailyLimit::firstOrCreate([]);
        $dailyLimit->update([
            'max_orders_per_day' => $request->max_orders_per_day,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'Pengaturan batas order dan jam operasional berhasil diperbarui.');
    }

    public function resetTransactions()
    {
        // Melakukan soft-delete pada semua pesanan agar statistik dashboard & laporan menjadi 0, 
        // namun riwayat pesanan (history) di sisi pelanggan tetap aman.
        \App\Models\Order::query()->delete();

        return redirect()->route('admin.dashboard')->with('status', 'Semua data pendapatan dan transaksi berhasil di-reset.');
    }
}
