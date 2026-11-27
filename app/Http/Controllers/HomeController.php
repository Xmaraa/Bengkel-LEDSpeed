<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->orderBy('harga')->take(6)->get();

        $produk = Product::active()->orderBy('harga')->get()
            ->groupBy('kategori')
            ->map(fn ($items) => $items->take(3));

        return view('home', compact('services', 'produk'));
    }

    public function products(Request $request)
    {
        $kategori = $request->query('kategori');
        if (! array_key_exists((string) $kategori, Product::KATEGORI)) {
            $kategori = null;
        }
        $q = trim((string) $request->query('q', ''));

        $query = Product::active();
        if ($kategori) {
            $query->where('kategori', $kategori);
        }
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('merek', 'like', "%{$q}%")
                  ->orWhere('spesifikasi', 'like', "%{$q}%");
            });
        }

        $produk = $query->orderBy('merek')->orderBy('harga')->get()->groupBy('kategori');

        return view('produk.index', compact('produk', 'kategori', 'q'));
    }
}
