<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->date('tanggal');
            $table->string('jam', 5);            // contoh: 09:00
            $table->text('keluhan')->nullable();
            $table->string('status', 20)->default('menunggu');
            $table->unsignedInteger('harga_saat_booking');
            $table->timestamps();
            $table->index(['tanggal', 'jam']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
