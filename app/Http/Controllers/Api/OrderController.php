<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // List all orders (admin-only)
    public function index()
    {
        $this->authorize('viewAny', Order::class);
        return response()->json(Order::all());
    }

    // Show order details
    public function show($id)
    {
        $order = Order::with('products', 'user', 'merchant')->findOrFail($id);
        $this->authorize('view', $order);
        return response()->json($order);
    }

    // Create order (user)
    public function store(Request $request)
    {
        $this->authorize('create', Order::class);

        // Validate request
        $request->validate([
            // 'user_id' => 'required|exists:users,id',
            'merchant_id' => 'required|exists:merchants,id',
            'status' => 'required|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        // Calculate total amount
        $totalAmount = 0;
        foreach ($request->products as $item) {
            $product = Product::findOrFail($item['product_id']);
            $totalAmount += $product->price * $item['quantity'];
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'merchant_id' => $request->merchant_id,
            'status' => $request->status,
            'total_amount' => $totalAmount,
        ]);

        // Create order_items
        foreach ($request->products as $item) {
            $product = Product::findOrFail($item['product_id']);
            $order->orderItems()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);
        }

        // Return order with items
        return response()->json($order->load('orderItems.product'), 201);
    }

    // Update order status (merchant/admin)
    public function update(Request $request, $id)
    {
        // Log::info("OrderController update", ["request" => $request->all()]);
        $order = Order::findOrFail($id);
        $this->authorize('update', $order);
        $order->update($request->only(['status']));
        return response()->json($order);
    }
}
