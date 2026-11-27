<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const KATEGORI = [
        'ban' => 'Ban Motor',
        'oli' => 'Oli Motor',
        'sparepart' => 'Sparepart',
    ];

    protected $fillable = ['kategori', 'merek', 'nama', 'spesifikasi', 'harga', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getKategoriLabelAttribute(): string
    {
        return self::KATEGORI[$this->kategori] ?? ucfirst($this->kategori);
    }
}
