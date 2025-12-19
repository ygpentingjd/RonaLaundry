<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductApiController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'message' => 'List products',
            'data' => $products
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'satuan' => 'required',
            'estimasi_waktu' => 'required',
            'harga_reguler' => 'required|numeric',
            'harga_kilat' => 'required|numeric',
            'deskripsi' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
             $path = $request->file('image')->store('products', 'public');
             $data['image'] = $path;
        }

        $product = Product::create($data);

        return response()->json([
            'message' => 'Product created',
            'data' => $product
        ], 201);
    }

    public function show($id_product)
    {
        $product = Product::find($id_product);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json(['data' => $product]);
    }

    public function update(Request $request, $id_product)
    {
        $product = Product::find($id_product);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'nama_barang' => 'sometimes|required',
            'satuan' => 'sometimes|required',
            'estimasi_waktu' => 'sometimes|required',
            'harga_reguler' => 'sometimes|required|numeric',
            'harga_kilat' => 'sometimes|required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        $product->update($data);

        return response()->json([
            'message' => 'Product updated',
            'data' => $product
        ]);
    }

    public function destroy($id_product)
    {
        $product = Product::find($id_product);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
