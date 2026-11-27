@extends('layouts.app')
@section('title', 'Beranda')
@section('content')
<section class="home-hero">
  <div>
    <h1>Ganti ban, oli, dan servis. Cepat dan jelas harganya.</h1>
    <p class="lead">Pilih jam servis lewat form reservasi, lihat daftar harga ban, oli, dan sparepart sebelum datang ke bengkel.</p>
    <div class="cta">
      <a class="btn link" href="{{ route('reservasi.create') }}">Reservasi sekarang</a>
      <a class="btn link ghost" href="{{ route('produk') }}">Lihat produk &amp; harga</a>
    </div>
  </div>
  <ul class="facts">
    <li><b>Senin–Sabtu</b>, 08.00–16.00 WIB</li>
    <li><b>2 kendaraan</b> per jam (kuota slot)</li>
    <li><b>Minggu</b> tutup</li>
  </ul>
</section>

<section class="sec">
  <h2>Kategori</h2>
  <div class="cats">
    @foreach(\App\Models\Product::KATEGORI as $key => $label)
      <a class="card cat" href="{{ route('produk', ['kategori' => $key]) }}">
        <h3>{{ $label }}</h3>
        <p>{{ ($produk[$key] ?? collect())->count() ? 'Lihat daftar harga' : 'Segera hadir' }} &rarr;</p>
      </a>
    @endforeach
    <a class="card cat" href="{{ route('reservasi.create') }}">
      <h3>Servis</h3>
      <p>Booking jam servis &rarr;</p>
    </a>
  </div>
</section>

<section class="sec">
  <h2>Harga layanan servis</h2>
  <div class="cards">
    @forelse($services as $s)
      <article class="card pcard">
        <span class="chip-tag">Servis</span>
        <h3>{{ $s->nama }}</h3>
        <p class="price">Rp{{ number_format($s->harga, 0, ',', '.') }}</p>
      </article>
    @empty
      <p class="empty">Belum ada layanan.</p>
    @endforelse
  </div>
</section>

@foreach(\App\Models\Product::KATEGORI as $key => $label)
  @if(isset($produk[$key]) && $produk[$key]->count())
  <section class="sec">
    <div class="sec-head">
      <h2>{{ $label }}</h2>
      <a href="{{ route('produk', ['kategori' => $key]) }}">Lihat semua</a>
    </div>
    <div class="cards">
      @foreach($produk[$key] as $p)
        @include('partials.product-card', ['p' => $p])
      @endforeach
    </div>
  </section>
  @endif
@endforeach

<section class="sec">
  <h2>Cara booking</h2>
  <ol class="steps">
    <li><b>Daftar / masuk</b> akun pelanggan.</li>
    <li><b>Pilih layanan, tanggal, dan jam</b> yang masih tersedia.</li>
    <li><b>Datang sesuai jadwal</b>, tunjukkan kode reservasi.</li>
  </ol>
</section>
<p class="note">Harga bersifat contoh dan dapat berubah. Konfirmasi harga akhir di bengkel.</p>
@endsection
