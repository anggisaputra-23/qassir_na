<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk
     */
    public function index(Request $request)
    {
        $category = $request->get('category');
        $products = Product::when($category, fn($q) => $q->where('category', $category))
            ->latest()
            ->get();

        return view('products.index', compact('products', 'category'));
    }

    /**
     * Simpan produk baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'price'      => 'required|numeric|min:0',
            'category'   => 'required|in:Makanan,Minuman',
            'has_stock'  => 'required|boolean',
            'stock'      => 'nullable|integer|min:0',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('products', 'public')
            : null;

        Product::create([
            'name'       => $validated['name'],
            'price'      => (int) $validated['price'],
            'category'   => $validated['category'],
            'has_stock'  => (bool) $validated['has_stock'],
            'stock'      => $validated['has_stock'] ? ($validated['stock'] ?? 0) : null,
            'image'      => $imagePath,
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Update produk
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'price'      => 'required|numeric|min:0',
            'category'   => 'required|in:Makanan,Minuman',
            'has_stock'  => 'required|boolean',
            'stock'      => 'nullable|integer|min:0',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'old_image'  => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);

        // Handle gambar
        if ($request->hasFile('image')) {
            // Upload baru, hapus gambar lama
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        } elseif (empty($request->old_image)) {
            // Tombol hapus ditekan, tanpa upload baru
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = null;
        }

        $product->update([
            'name'       => $validated['name'],
            'price'      => (int) $validated['price'],
            'category'   => $validated['category'],
            'has_stock'  => (bool) $validated['has_stock'],
            'stock'      => $validated['has_stock'] ? ($validated['stock'] ?? 0) : null,
            'image'      => $product->image,
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Hapus produk
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}
