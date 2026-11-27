@extends('layouts.app')
@section('title', 'Edit Produk')
@section('content')
<h2>Edit produk</h2>
<div class="card" style="margin-top:1rem">
  <form class="row" method="POST" action="{{ route('admin.produk.update.full', $product) }}" style="margin-top:0">
    @csrf @method('PUT')
    <div>
      <label for="kategori">Kategori</label>
      <select id="kategori" name="kategori" required>
        @foreach(\App\Models\Product::KATEGORI as $key => $label)
          <option value="{{ $key }}" @selected(old('kategori', $product->kategori) === $key)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
    <div><label for="merek">Merek</label><input id="merek" name="merek" value="{{ old('merek', $product->merek) }}" required></div>
    <div><label for="nama">Nama produk</label><input id="nama" name="nama" value="{{ old('nama', $product->nama) }}" required></div>
    <div><label for="spesifikasi">Ukuran / spesifikasi</label><input id="spesifikasi" name="spesifikasi" value="{{ old('spesifikasi', $product->spesifikasi) }}"></div>
    <div><label for="harga">Harga (Rp)</label><input id="harga" name="harga" type="number" min="0" step="1000" value="{{ old('harga', $product->harga) }}" required></div>
    <button class="btn sm" style="padding:.65rem 1rem" type="submit">Simpan</button>
    <a class="btn sm ghost link" style="padding:.65rem 1rem" href="{{ route('admin.produk') }}">Batal</a>
  </form>
  @if($errors->any())<p class="err" role="alert">{{ $errors->first() }}</p>@endif
</div>
@endsection