<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerCartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product.category')->get();
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->qty * $item->product->price;
        });

        return view('customer.cart.index', compact('cartItems', 'totalPrice', 'user'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $product = Product::active()->findOrFail($request->product_id);

        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->qty += $request->qty;
            if ($request->filled('notes')) {
                $cartItem->notes = $request->notes;
            }
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'qty' => $request->qty,
                'notes' => $request->notes,
            ]);
        }

        return redirect()->route('customer.cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang belanja!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cart_items,id',
            'qty' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $cartItem = CartItem::where('user_id', $user->id)->findOrFail($request->id);

        $cartItem->update([
            'qty' => $request->qty,
            'notes' => $request->notes,
        ]);

        return redirect()->route('customer.cart.index')->with('success', 'Keranjang berhasil diperbarui!');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cart_items,id',
        ]);

        $user = Auth::user();
        $cartItem = CartItem::where('user_id', $user->id)->findOrFail($request->id);
        $cartItem->delete();

        return redirect()->route('customer.cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    public function clear()
    {
        $user = Auth::user();
        CartItem::where('user_id', $user->id)->delete();

        return redirect()->route('customer.cart.index')->with('success', 'Keranjang belanja berhasil dikosongkan.');
    }
}
