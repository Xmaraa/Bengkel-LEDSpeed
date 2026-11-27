<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('nama')->get();

        return view('admin.services', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'harga' => ['required', 'integer', 'min:0'],
        ]);
        Service::create($data);

        return back()->with('ok', 'Layanan ditambahkan.');
    }

    public function toggle(Service $service)
    {
        $service->update(['is_active' => ! $service->is_active]);

        return back()->with('ok', 'Status layanan diperbarui.');
    }

    public function destroy(Service $service)
    {
        if ($service->reservations()->exists()) {
            return back()->with('error', 'Layanan sudah dipakai reservasi. Nonaktifkan saja.');
        }
        $service->delete();

        return back()->with('ok', 'Layanan dihapus.');
    }
}
