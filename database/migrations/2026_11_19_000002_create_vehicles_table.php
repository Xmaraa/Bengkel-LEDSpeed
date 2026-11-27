<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('jenis', 10);        // motor / mobil
            $table->string('plat_nomor', 12);
            $table->timestamps();
            $table->unique(['user_id', 'plat_nomor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
