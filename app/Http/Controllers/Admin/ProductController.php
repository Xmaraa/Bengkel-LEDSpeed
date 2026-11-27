<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('kategori')->orderBy('merek')->orderBy('nama')->get();

        return view('admin.products', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori' => ['required', Rule::in(array_keys(Product::KATEGORI))],
            'merek' => ['required', 'string', 'max:60'],
            'nama' => ['required', 'string', 'max:120'],
            'spesifikasi' => ['nullable', 'string', 'max:120'],
            'harga' => ['required', 'integer', 'min:0'],
        ]);
        Product::create($data);

        return back()->with('ok', 'Produk ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate(['harga' => ['required', 'integer', 'min:0']]);
        $product->update($data);

        return back()->with('ok', 'Harga diperbarui.');
    }

    public function toggle(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        return back()->with('ok', 'Status produk diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('ok', 'Produk dihapus.');
    }
}
