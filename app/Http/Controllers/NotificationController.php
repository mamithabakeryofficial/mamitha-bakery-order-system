<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        $notification->update(['is_read' => true]);
        
        if ($notification->order_id) {
            return redirect()->route('customer.orders.show', $notification->order_id);
        }
        
        return back();
    }
    
    public function markAllAsRead()
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        
        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }
}
