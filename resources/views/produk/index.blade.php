@extends('layouts.app')
@section('title', 'Produk & Harga')
@section('content')
<h2>Produk &amp; harga</h2>

<form class="filter" method="GET" action="{{ route('produk') }}">
  @if($kategori)<input type="hidden" name="kategori" value="{{ $kategori }}">@endif
  <input type="search" name="q" value="{{ $q }}" placeholder="Cari merek, nama, atau ukuran (mis. 80/90-14)" aria-label="Cari produk">
  <button class="btn sm" type="submit">Cari</button>
</form>

<div class="chips" role="group" aria-label="Kategori">
  <a href="{{ route('produk', array_filter(['q' => $q])) }}" @if(!$kategori) aria-current="true" @endif>Semua</a>
  @foreach(\App\Models\Product::KATEGORI as $key => $label)
    <a href="{{ route('produk', array_filter(['kategori' => $key, 'q' => $q])) }}" @if($kategori === $key) aria-current="true" @endif>{{ $label }}</a>
  @endforeach
</div>

@forelse($produk as $key => $items)
  <section class="sec">
    <h2>{{ \App\Models\Product::KATEGORI[$key] ?? ucfirst($key) }}</h2>
    <div class="cards">
      @foreach($items as $p)
        @include('partials.product-card', ['p' => $p])
      @endforeach
    </div>
  </section>
@empty
  <p class="empty" style="margin-top:1.2rem">Tidak ada produk yang cocok.</p>
@endforelse

<div class="cta" style="margin-top:1.5rem">
  <a class="btn link" href="{{ route('reservasi.create') }}">Reservasi pemasangan / servis</a>
</div>
<p class="note">Harga bersifat contoh dan dapat berubah. Konfirmasi harga akhir di bengkel.</p>
@endsection
