<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Default date range based on period
        if (!$startDate || !$endDate) {
            switch ($period) {
                case 'today':
                    $startDate = today()->toDateString();
                    $endDate = today()->toDateString();
                    break;
                case 'week':
                    $startDate = now()->startOfWeek()->toDateString();
                    $endDate = now()->endOfWeek()->toDateString();
                    break;
                case 'month':
                    $startDate = now()->startOfMonth()->toDateString();
                    $endDate = now()->endOfMonth()->toDateString();
                    break;
                case 'year':
                    $startDate = now()->startOfYear()->toDateString();
                    $endDate = now()->endOfYear()->toDateString();
                    break;
            }
        }

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Revenue data
        $totalRevenue = Order::where('payment_status', 'settlement')
            ->whereBetween('order_date', [$start, $end])
            ->sum('total_price');

        $totalOrders = Order::whereBetween('order_date', [$start, $end])->count();

        $paidOrders = Order::where('payment_status', 'settlement')
            ->whereBetween('order_date', [$start, $end])
            ->count();

        $cancelledOrders = Order::where('order_status', 'dibatalkan')
            ->whereBetween('order_date', [$start, $end])
            ->count();

        $avgOrderValue = $paidOrders > 0 ? $totalRevenue / $paidOrders : 0;

        // Daily revenue chart data (last 30 days or within range)
        $dailyRevenue = Order::where('payment_status', 'settlement')
            ->whereBetween('order_date', [$start, $end])
            ->select(DB::raw('DATE(order_date) as date'), DB::raw('SUM(total_price) as revenue'), DB::raw('COUNT(*) as orders'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top selling products
        $topProducts = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->where('orders.payment_status', 'settlement')
            ->whereBetween('orders.order_date', [$start, $end])
            ->select(
                'products.name',
                DB::raw('SUM(order_details.qty) as total_qty'),
                DB::raw('SUM(order_details.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // Revenue by category
        $revenueByCategory = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.payment_status', 'settlement')
            ->whereBetween('orders.order_date', [$start, $end])
            ->select(
                'categories.name',
                DB::raw('SUM(order_details.subtotal) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        // Recent paid orders
        $recentPaidOrders = Order::with('user')
            ->where('payment_status', 'settlement')
            ->whereBetween('order_date', [$start, $end])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'totalRevenue',
            'totalOrders',
            'paidOrders',
            'cancelledOrders',
            'avgOrderValue',
            'dailyRevenue',
            'topProducts',
            'revenueByCategory',
            'recentPaidOrders',
            'startDate',
            'endDate',
            'period'
        ));
    }
}
