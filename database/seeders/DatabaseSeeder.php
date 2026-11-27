<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun contoh (ganti password admin sebelum online!)
        User::updateOrCreate(['email' => 'admin@ledspeed.test'], [
            'name' => 'Admin LED Speed', 'phone' => '081200000000',
            'password' => Hash::make('admin12345'), 'role' => 'admin',
        ]);
        $budi = User::updateOrCreate(['email' => 'budi@example.test'], [
            'name' => 'Budi Santoso', 'phone' => '081234567890',
            'password' => Hash::make('password123'), 'role' => 'pelanggan',
        ]);

        $layanan = [
            ['Ganti oli', 85000], ['Servis berkala', 250000], ['Tune up', 180000],
            ['Ganti kampas rem', 150000], ['Spooring & balancing', 200000], ['Servis AC mobil', 300000],
        ];
        foreach ($layanan as [$nama, $harga]) {
            Service::firstOrCreate(['nama' => $nama], ['harga' => $harga, 'is_active' => true]);
        }

        // Contoh reservasi (hanya jika tabel masih kosong)
        if (Reservation::count() === 0) {
            $motor = Vehicle::firstOrCreate(['user_id' => $budi->id, 'plat_nomor' => 'L 4521 KX'], ['jenis' => 'motor']);
            $oli = Service::where('nama', 'Ganti oli')->first();
            $tune = Service::where('nama', 'Tune up')->first();

            Reservation::create([
                'kode' => 'RSV-DEMO-0001', 'user_id' => $budi->id, 'vehicle_id' => $motor->id,
                'service_id' => $oli->id, 'tanggal' => now()->addDay()->toDateString(), 'jam' => '09:00',
                'keluhan' => 'Oli sudah 3 bulan', 'status' => 'dikonfirmasi', 'harga_saat_booking' => $oli->harga,
            ]);
            Reservation::create([
                'kode' => 'RSV-DEMO-0002', 'user_id' => $budi->id, 'vehicle_id' => $motor->id,
                'service_id' => $tune->id, 'tanggal' => now()->subDays(14)->toDateString(), 'jam' => '10:00',
                'status' => 'selesai', 'harga_saat_booking' => $tune->harga,
            ]);
        }

        $this->call(ProductSeeder::class);
    }
}
