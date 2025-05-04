<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('orderItems.product')->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $total = 0;
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => 0,
            'status' => 'pending',
        ]);

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $orderItem = $order->orderItems()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);
            $total += $product->price * $item['quantity'];
        }

        $order->update(['total' => $total]);
        return response()->json($order->load('orderItems.product'), 201);
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return response()->json($order->load('orderItems.product'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,canceled',
        ]);

        $order->update($validated);
        return response()->json($order);
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        $order->delete();
        return response()->json(['message' => 'Order deleted']);
    }
}
