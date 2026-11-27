<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    /** Form reservasi. */
    public function create()
    {
        $services = Service::where('is_active', true)->orderBy('nama')->get();

        return view('reservations.create', compact('services'));
    }

    /** JSON sisa kuota tiap jam pada satu tanggal (dipakai reservasi.js). */
    public function slots(Request $request)
    {
        $request->validate(['tanggal' => ['required', 'date_format:Y-m-d']]);
        $tgl = Carbon::createFromFormat('Y-m-d', $request->tanggal)->startOfDay();

        if ($tgl->isSunday()) {
            return response()->json(['closed' => true, 'slots' => []]);
        }

        $terpakai = Reservation::where('tanggal', $tgl->toDateString())
            ->aktif()
            ->selectRaw('jam, COUNT(*) as n')
            ->groupBy('jam')
            ->pluck('n', 'jam');

        $slots = collect(Reservation::JAM)->map(fn ($j) => [
            'jam' => $j,
            'sisa' => max(0, Reservation::KUOTA - (int) ($terpakai[$j] ?? 0)),
            'lewat' => $tgl->lt(today()) || ($tgl->isToday() && (int) substr($j, 0, 2) <= now()->hour),
        ])->values();

        return response()->json(['closed' => false, 'slots' => $slots]);
    }

    /** Simpan reservasi ke database. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis' => ['required', Rule::in(['motor', 'mobil'])],
            'plat' => ['required', 'string', 'max:12'],
            'service_id' => ['required', 'integer'],
            'tanggal' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'jam' => ['required', Rule::in(Reservation::JAM)],
            'keluhan' => ['nullable', 'string', 'max:500'],
        ], [
            'plat.required' => 'Isi plat nomor kendaraan.',
            'tanggal.required' => 'Pilih tanggal servis.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini.',
            'jam.required' => 'Pilih jam yang masih tersedia.',
            'jam.in' => 'Jam tidak valid.',
        ]);

        $tgl = Carbon::createFromFormat('Y-m-d', $data['tanggal'])->startOfDay();

        if ($tgl->isSunday()) {
            return back()->withErrors(['tanggal' => 'Bengkel tutup hari Minggu.'])->withInput();
        }
        if ($tgl->isToday() && (int) substr($data['jam'], 0, 2) <= now()->hour) {
            return back()->withErrors(['jam' => 'Jam tersebut sudah lewat.'])->withInput();
        }

        $service = Service::where('is_active', true)->find($data['service_id']);
        if (! $service) {
            return back()->withErrors(['service_id' => 'Layanan tidak tersedia.'])->withInput();
        }

        $user = $request->user();

        // Transaksi + lock supaya kuota tidak terlampaui saat dua orang memesan bersamaan.
        $reservation = DB::transaction(function () use ($data, $service, $user) {
            $terpakai = Reservation::where('tanggal', $data['tanggal'])
                ->where('jam', $data['jam'])
                ->aktif()
                ->lockForUpdate()
                ->count();

            if ($terpakai >= Reservation::KUOTA) {
                return null;
            }

            $vehicle = Vehicle::firstOrCreate(
                ['user_id' => $user->id, 'plat_nomor' => strtoupper(trim($data['plat']))],
                ['jenis' => $data['jenis']]
            );

            return Reservation::create([
                'kode' => 'RSV-'.now()->format('ymd').'-'.strtoupper(Str::random(4)),
                'user_id' => $user->id,
                'vehicle_id' => $vehicle->id,
                'service_id' => $service->id,
                'tanggal' => $data['tanggal'],
                'jam' => $data['jam'],
                'keluhan' => $data['keluhan'] ?? null,
                'status' => 'menunggu',
                'harga_saat_booking' => $service->harga,
            ]);
        });

        if (! $reservation) {
            return back()->withErrors(['jam' => 'Slot baru saja penuh. Pilih jam lain.'])->withInput();
        }

        return redirect()->route('riwayat')->with('ok', "Reservasi {$reservation->kode} terkirim.");
    }

    /** Riwayat milik pelanggan yang sedang login. */
    public function index(Request $request)
    {
        $reservations = Reservation::with(['service', 'vehicle'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('tanggal')
            ->orderByDesc('jam')
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    public function cancel(Request $request, Reservation $reservation)
    {
        abort_unless((int) $reservation->user_id === (int) $request->user()->id, 403);

        if ($reservation->status !== 'menunggu') {
            return back()->with('error', 'Hanya reservasi berstatus menunggu yang bisa dibatalkan.');
        }

        $reservation->update(['status' => 'dibatalkan']);

        return back()->with('ok', 'Reservasi dibatalkan.');
    }
}
