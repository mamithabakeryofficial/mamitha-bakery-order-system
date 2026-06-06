<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Count summaries
        $totalOrders = $user->orders()->count();
        $totalTransactions = $user->orders()->where('payment_status', 'settlement')->sum('total_price');
        $activeOrders = $user->orders()->whereNotIn('order_status', ['selesai', 'dibatalkan'])->count();
        $completedOrders = $user->orders()->where('order_status', 'selesai')->count();
        
        // Get recent active orders
        $recentOrders = $user->orders()->latest()->take(5)->get();

        return view('customer.dashboard', compact(
            'user', 
            'totalOrders', 
            'totalTransactions', 
            'activeOrders', 
            'completedOrders',
            'recentOrders'
        ));
    }
}
