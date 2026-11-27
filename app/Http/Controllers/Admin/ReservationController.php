<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user', 'vehicle', 'service'])
            ->orderByDesc('tanggal')
            ->orderByDesc('jam')
            ->get();

        $stats = ['total' => $reservations->count()];
        foreach (['menunggu', 'dikonfirmasi', 'dikerjakan', 'selesai'] as $s) {
            $stats[$s] = $reservations->where('status', $s)->count();
        }

        $statuses = Reservation::STATUS;

        return view('admin.reservations', compact('reservations', 'stats', 'statuses'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $data = $request->validate(['status' => ['required', Rule::in(Reservation::STATUS)]]);
        $reservation->update($data);

        return back()->with('ok', "Status {$reservation->kode} diperbarui: {$data['status']}.");
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return back()->with('ok', 'Reservasi dihapus.');
    }
}
