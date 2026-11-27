<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    public const JAM = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'];
    public const KUOTA = 2; // kendaraan per jam
    public const STATUS = ['menunggu', 'dikonfirmasi', 'dikerjakan', 'selesai', 'ditolak', 'dibatalkan'];

    protected $fillable = [
        'kode', 'user_id', 'vehicle_id', 'service_id',
        'tanggal', 'jam', 'keluhan', 'status', 'harga_saat_booking',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    /** Reservasi yang masih memakai kuota (bukan ditolak / dibatalkan). */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->whereNotIn('status', ['ditolak', 'dibatalkan']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
