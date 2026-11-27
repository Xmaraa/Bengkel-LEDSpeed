@extends('layouts.app')
@section('title', 'Reservasi')
@section('content')
<div class="hero">
  <div>
    <h1>Pilih jam servis. Garasi siap tancap gas.</h1>
    <p class="lead">Booking layanan dan jadwal dalam satu form. Slot yang penuh otomatis tidak bisa dipilih.</p>
    <ul class="facts">
      <li><b>Senin–Sabtu</b>, 08.00–16.00 WIB</li>
      <li><b>{{ \App\Models\Reservation::KUOTA }} kendaraan</b> per jam (kuota slot)</li>
      <li><b>Minggu</b> tutup</li>
    </ul>
  </div>
  <form class="card" id="form" method="POST" action="{{ route('reservasi.store') }}" novalidate>
    @csrf
    <h2>Buat reservasi</h2>
    <p class="note" style="margin:.4rem 0 0">Atas nama <b>{{ auth()->user()->name }}</b> · {{ auth()->user()->phone }}</p>
    <div class="grid2">
      <div>
        <label for="jenis">Jenis kendaraan</label>
        <select id="jenis" name="jenis">
          <option value="motor" @selected(old('jenis') === 'motor')>Motor</option>
          <option value="mobil" @selected(old('jenis') === 'mobil')>Mobil</option>
        </select>
      </div>
      <div>
        <label for="plat">Plat nomor</label>
        <input id="plat" name="plat" value="{{ old('plat') }}" placeholder="L 1234 AB" maxlength="12">
      </div>
    </div>
    <label for="layanan">Layanan</label>
    <select id="layanan" name="service_id">
      @foreach($services as $s)
        <option value="{{ $s->id }}" @selected(old('service_id') == $s->id)>{{ $s->nama }} – Rp{{ number_format($s->harga, 0, ',', '.') }}</option>
      @endforeach
    </select>
    <label for="tgl">Tanggal</label>
    <input id="tgl" name="tanggal" type="date" min="{{ today()->toDateString() }}" value="{{ old('tanggal', today()->toDateString()) }}">
    <label id="lbl-slot">Jam</label>
    <div class="slots" id="slots" role="group" aria-labelledby="lbl-slot" data-url="{{ route('reservasi.slots') }}" data-jam="{{ old('jam') }}"></div>
    <input type="hidden" name="jam" id="jam" value="{{ old('jam') }}">
    <label for="keluhan">Keluhan (opsional)</label>
    <textarea id="keluhan" name="keluhan" maxlength="500" placeholder="Contoh: mesin kasar saat dingin">{{ old('keluhan') }}</textarea>
    @if($errors->any())<p class="err" role="alert">{{ $errors->first() }}</p>@endif
    <button class="btn" type="submit">Kirim reservasi</button>
  </form>
</div>
@endsection
@push('scripts')<script src="{{ asset('js/reservasi.js') }}"></script>@endpush
