<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /** Harga CONTOH untuk belajar. Ubah lewat menu Admin > Kelola Produk. */
    public function run(): void
    {
        $items = [
            // [kategori, merek, nama, spesifikasi, harga]
            ['ban', 'FDR', 'Sport XR Evo', '70/90-14 Tubeless', 165000],
            ['ban', 'FDR', 'Sport XR Evo', '80/90-14 Tubeless', 185000],
            ['ban', 'IRC', 'NR 77', '80/90-14 Tubeless', 205000],
            ['ban', 'Corsa', 'Platinum R99', '90/80-14 Tubeless', 215000],
            ['ban', 'Michelin', 'City Grip', '90/90-14 Tubeless', 310000],
            ['ban', 'Dunlop', 'D307', '100/80-17 Tubeless', 360000],

            ['oli', 'Yamalube', 'Matic', '0,8 L', 58000],
            ['oli', 'Federal', 'Matic', '0,8 L', 52000],
            ['oli', 'Shell', 'Advance AX7', '1 L', 78000],
            ['oli', 'Castrol', 'Power1 4T', '1 L', 90000],
            ['oli', 'Motul', '3000 Plus 4T', '1 L', 82000],

            ['sparepart', 'Aspira', 'Kampas rem depan', 'Motor matic', 35000],
            ['sparepart', 'NGK', 'Busi standar', 'Motor 4 tak', 25000],
            ['sparepart', 'Aspira', 'Filter udara', 'Motor matic', 45000],
            ['sparepart', 'Aspira', 'V-belt', 'Motor matic 125 cc', 120000],
            ['sparepart', 'GS Astra', 'Aki kering', '12V 3,5 Ah', 175000],
        ];

        foreach ($items as [$kategori, $merek, $nama, $spek, $harga]) {
            Product::firstOrCreate(
                ['kategori' => $kategori, 'merek' => $merek, 'nama' => $nama, 'spesifikasi' => $spek],
                ['harga' => $harga, 'is_active' => true]
            );
        }
    }
}
