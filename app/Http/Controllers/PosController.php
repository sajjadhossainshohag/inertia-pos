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
        // Fetch orders with filters
        $orders = Order::latest()
            ->when(request()->get('search'), function ($query, $search) {
                $query->whereLike('order_id', '%' . $search . '%');
            })->when(request()->get('from_date'), function ($query, $from_date) {
                $query->whereDate('created_at', '>=', $from_date);
            })->when(request()->get('to_date'), function ($query, $to_date) {
                $query->whereDate('created_at', '<=', $to_date);
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
        return to_route('pos.orders')->with('success', 'Order placed successfully!');
    }
}
