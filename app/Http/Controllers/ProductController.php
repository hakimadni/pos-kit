<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('products/Index', [
            'products' => Product::with(['category', 'menuset'])->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('products/Create', [
            'categories' => \App\Models\Category::all(),
            'menuSets' => \App\Models\MenuSet::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:products|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|numeric|min:0',
            'set_id' => 'nullable|numeric|min:0',
            'desc' => 'required|string',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('message', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        return inertia('products/Show', [
            'product' => $product,
            'category' => $product->category,
            'menuSet' => $product->menuSet,
            'menuSets' => \App\Models\MenuSet::all(),
            'categories' => \App\Models\Category::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return inertia('products/Edit', [
            'product' => $product,
            'category' => $product->category,
            'menuSet' => $product->menuSet,
            'menuSets' => \App\Models\MenuSet::all(),
            'categories' => \App\Models\Category::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|numeric|min:0',
            'set_id' => 'nullable|numeric|min:0',
            'desc' => 'required|string',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('message', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('message', 'Product deleted successfully.');
    }
}
