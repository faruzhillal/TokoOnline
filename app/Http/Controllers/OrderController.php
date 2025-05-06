<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produk;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function addToCart($id)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $produk = Produk::findOrFail($id);

        $order = Order::firstOrCreate(
            ['customer_id' => $customer->id, 'status' => 'pending'],
            ['total_harga' => 0]
        );

        $orderItem = OrderItem::firstOrCreate(
            ['order_id' => $order->id, 'produk_id' => $produk->id],
            ['quantity' => 1, 'harga' => $produk->harga]
        );

        if (!$orderItem->wasRecentlyCreated) {
            $orderItem->quantity++;
            $orderItem->save();
        }

        $order->total_harga += $produk->harga;
        $order->save();

        return redirect()->route('order.cart')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function viewCart()
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $order = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->first();

        if ($order) {
            $order->load('orderItems.produk');
        }

        return view('v_order.cart', compact('order'));
    }

    // app/Http/Controllers/OrderController.php
    public function updateCart(Request $request, $id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $orderItem->quantity = $request->quantity;
        $orderItem->save();

        // Update total harga di order
        $order = $orderItem->order;
        $order->total_harga = $order->orderItems->sum(function ($item) {
            return $item->harga * $item->quantity;
        });
        $order->save();

        return redirect()->back()->with('success', 'Keranjang berhasil diupdate');
    }

    public function removeFromCart($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $order = $orderItem->order;
        $orderItem->delete();

        // Update total harga
        $order->total_harga = $order->orderItems->sum(function ($item) {
            return $item->harga * $item->quantity;
        });
        $order->save();

        return redirect()->back()->with('success', 'Item berhasil dihapus');
    }
}
