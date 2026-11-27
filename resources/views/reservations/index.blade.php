@extends('layouts.app')
@section('title', 'Riwayat')
@section('content')
<h2>Riwayat reservasi</h2>
<div class="list" style="margin-top:1rem">
  @forelse($reservations as $r)
    <article class="card rsv">
      <div><h3>{{ $r->service->nama }}</h3></div>
      <span class="badge s-{{ ucfirst($r->status) }}">{{ ucfirst($r->status) }}</span>
      <p>{{ $r->tanggal->translatedFormat('l, j F Y') }}, pukul {{ $r->jam }} WIB<br>
        {{ ucfirst($r->vehicle->jenis) }} {{ $r->vehicle->plat_nomor }} · {{ $r->kode }} · Rp{{ number_format($r->harga_saat_booking, 0, ',', '.') }}
        @if($r->keluhan)<br>Keluhan: {{ $r->keluhan }}@endif
      </p>
      <div>
        @if($r->status === 'menunggu')
          <form method="POST" action="{{ route('riwayat.batal', $r) }}" onsubmit="return confirm('Batalkan reservasi {{ $r->kode }}?')">
            @csrf @method('PATCH')
            <button class="btn sm danger" type="submit">Batalkan</button>
          </form>
        @endif
      </div>
    </article>
  @empty
    <div class="empty">Belum ada reservasi. Buat yang pertama di menu Reservasi.</div>
  @endforelse
</div>
@endsection
