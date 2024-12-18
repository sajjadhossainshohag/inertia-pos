<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use Mockery\Matcher\Type;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Product\StoreProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get products
        $products = Product::query()->latest()->paginate(5);

        return Inertia::render('Product/Index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Product/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // Store product & variations
        try {
            DB::beginTransaction();
            // Save thumbnail
            $thumbnail = $request->file('thumbnail')->store('products');

            // Store product
            $product = Product::create([
                'name' => $request->get('name'),
                'sku' => $request->get('sku'),
                'unit' => $request->get('unit'),
                'thumbnail' => $thumbnail,
                'unit_value' => $request->get('unitValue'),
                'selling_price' => $request->get('sellingPrice'),
                'purchase_price' => $request->get('purchasePrice'),
                'discount' => $request->get('discount'),
                'tax' => $request->get('tax'),
            ]);

            // Temporary variable for storing variations data
            $variations = [];

            // Add variation to variations variable with product id
            foreach ($request->get('variations', []) as $variation) {
                $variations[] = [
                    'attributes' => json_encode($variation['attributes']),
                    'product_id' => $product->id,
                    'sell_price' => $variation['sellPrice'],
                    'purchase_price' => $variation['purchasePrice']
                ];
            }

            // If variations is exists then store
            if (count($variations) > 0) {
                // Insert product variations 
                ProductVariation::insert($variations);
            }

            DB::commit();

            // Redirecting to products page
            return to_route('products.index')->with('success', __('Product has been created successfully!'));
        } catch (\Throwable $th) {
            return to_route('products.index')->with('error', __('Sorry, something went wrong!'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        // Fetch product
        $product = Product::findOrFail($id);

        // Deleting product thumbnail 
        Storage::delete($product->thumbnail);

        // Deleting product
        $product->variations()->delete();
        $product->delete();

        // Redirect to products
        return to_route('products.index');
    }
}
