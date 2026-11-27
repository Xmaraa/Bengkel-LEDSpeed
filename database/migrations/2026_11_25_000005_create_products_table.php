<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('kategori', 20); // ban | oli | sparepart
            $table->string('merek', 60);
            $table->string('nama', 120);
            $table->string('spesifikasi', 120)->nullable();
            $table->unsignedInteger('harga');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
