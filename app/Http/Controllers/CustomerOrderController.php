<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->withTrashed()->latest()->paginate(10);
        
        return view('customer.orders.index', compact('orders', 'user'));
    }

    public function show(Order $order)
    {
        // Pastikan pesanan ini milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['details.product', 'payment']);
        $user = Auth::user();
        
        return view('customer.orders.show', compact('order', 'user'));
    }

    public function invoice(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load('details.product');
        
        $pdf = Pdf::loadView('customer.orders.invoice', compact('order'));
        
        return $pdf->download('Invoice-' . $order->invoice_number . '.pdf');
    }
}
