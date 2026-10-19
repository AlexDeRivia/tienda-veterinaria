<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CartDetail;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('app.admin.productos.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        return view('app.admin.productos.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'is_active' => 'sometimes|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:512',
        ]);
        unset($validated['image']);
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;
        $image = $request->file('image');
        $content = $image?->get();
        DB::transaction(function () use ($validated, $image, $content) {
            $product = Product::create([
                ...$validated,
                'image_hash' => $content === null ? null : hash('sha256', $content),
            ]);
            if ($image) {
                DB::table('product_images')->insert([
                    'product_id' => $product->id,
                    'mime_type' => $image->getMimeType(),
                    'content' => $content,
                ]);
            }
        });
        return redirect()->route('admin.productos');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $hasCartHistory = CartDetail::where('product_id', $product->id)->exists();
        return view('app.admin.productos.show', compact('product', 'hasCartHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $suppliers = Supplier::all();
        return view('app.admin.productos.edit', compact('product', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'is_active' => 'sometimes|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:512',
        ]);
        unset($validated['image']);
        if ($request->has('is_active')) {
            $validated['is_active'] = $request->boolean('is_active');
        }

        $image = $request->file('image');
        $content = $image?->get();
        $oldPath = $product->image_path;
        if ($image) {
            $validated['image_hash'] = hash('sha256', $content);
            $validated['image_path'] = null;
        }
        DB::transaction(function () use ($product, $validated, $image, $content) {
            $product->update($validated);
            if ($image) {
                DB::table('product_images')->updateOrInsert(
                    ['product_id' => $product->id],
                    ['mime_type' => $image->getMimeType(), 'content' => $content]
                );
            }
        });
        if ($image && $oldPath) {
            Storage::disk('public')->delete($oldPath);
        }
        return redirect()->route('admin.productos');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (CartDetail::where('product_id', $product->id)->exists()) {
            return back()->withErrors([
                'product' => 'No se puede eliminar un producto que aparece en un carrito o una compra.',
            ]);
        }

        $path = $product->image_path;
        $product->delete();
        if ($path) {
            Storage::disk('public')->delete($path);
        }
        return redirect()->route('admin.productos');
    }
}
