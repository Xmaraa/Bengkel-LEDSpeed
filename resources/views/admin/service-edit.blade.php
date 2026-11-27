@extends('layouts.app')
@section('title', 'Edit Layanan')
@section('content')
<h2>Edit layanan</h2>
<div class="card" style="margin-top:1rem">
  <form class="row" method="POST" action="{{ route('admin.layanan.update', $service) }}" style="margin-top:0">
    @csrf @method('PUT')
    <div><label for="nama">Nama layanan</label><input id="nama" name="nama" value="{{ old('nama', $service->nama) }}" required></div>
    <div><label for="harga">Harga (Rp)</label><input id="harga" name="harga" type="number" min="0" step="1000" value="{{ old('harga', $service->harga) }}" required></div>
    <button class="btn sm" style="padding:.65rem 1rem" type="submit">Simpan</button>
    <a class="btn sm ghost link" style="padding:.65rem 1rem" href="{{ route('admin.layanan') }}">Batal</a>
  </form>
  @if($errors->any())<p class="err" role="alert">{{ $errors->first() }}</p>@endif
</div>
@endsection