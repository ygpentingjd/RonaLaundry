<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // LIST DATA
    public function index()
    {
        return response()->json(Product::all());
    }

    public function adminIndex()
    {
        return \Inertia\Inertia::render('admin/Products', [
            'products' => Product::all()
        ]);
    }
    // STORE DATA
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_barang' => 'required|string',
            'satuan' => 'required|in:kg,pcs,meter',
            'estimasi_waktu' => 'required|string',
            'harga_reguler' => 'required|numeric',
            'harga_kilat' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240'
        ]);

        if ($request->hasFile('image')) {
            // Simpan ke storage/app/public/products
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return response()->json(['message' => 'Produk berhasil ditambahkan'], 201);
    }

    // SHOW DETAIL PRODUK
    public function show($id)
    {
        return response()->json(Product::findOrFail($id));
    }

    // UPDATE PRODUK
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required',
            'estimasi_waktu' => 'required',
            'harga_reguler' => 'required|numeric',
            'harga_kilat' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240'
        ]);

        // jika ada gambar baru
        if ($request->hasFile('image')) {
            // hapus file lama
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            // simpan file baru
            $product->image = $request->file('image')->store('products', 'public');
        }

        // update field lain
        $product->nama_barang = $request->nama_barang;
        $product->satuan = $request->satuan;
        $product->estimasi_waktu = $request->estimasi_waktu;
        $product->harga_reguler = $request->harga_reguler;
        $product->harga_kilat = $request->harga_kilat;
        $product->deskripsi = $request->deskripsi;

        $product->save();

        return response()->json([
            'message' => 'Produk berhasil diperbarui!'
        ]);
    }

    // DELETE PRODUK
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // hapus gambar dari storage
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus!'
        ]);
    }
}