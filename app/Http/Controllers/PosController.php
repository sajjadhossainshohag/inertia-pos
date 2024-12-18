<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PosController extends Controller
{
    public function index()
    {
        // Fetch products
        $products = Product::with('variations')->latest()->paginate();

        return Inertia::render('Pos/Index', [
            'products' => $products,
        ]);
    }

    public function orders()
    {
        // Fetch orders
        $orders = Order::latest()
            ->when(request()->get('search'), function ($query, $search) {
                $query->whereLike('order_id', '%'.$search.'%');
            })->paginate();

        return Inertia::render('Orders', [
            'orders' => $orders,
        ]);
    }

    public function placeOrder(Request $request)
    {
        // Validate
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'nullable|integer',
            'items.*.variation_id' => 'nullable|integer',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.tax' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        // Store order data
        Order::create([
            'order_id' => strtoupper(Str::random(10)),
            'items' => $request->get('items', []),
            'tax' => $validated['tax'],
            'sub_total' => $validated['subtotal'],
            'total' => $validated['total'],
        ]);

        // Redirecting back
        return back()->with('success', 'Order placed successfully!');
    }
}
