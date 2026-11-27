@extends('layouts.app')
@section('title', 'Kelola Produk')
@section('content')
<h2>Kelola produk &amp; harga TES</h2>
<div class="card" style="margin-top:1rem">
  <form class="row" method="POST" action="{{ route('admin.produk.simpan') }}" style="margin-top:0">
    @csrf
    <div>
      <label for="kategori">Kategori</label>
      <select id="kategori" name="kategori" required>
        @foreach(\App\Models\Product::KATEGORI as $key => $label)
          <option value="{{ $key }}" @selected(old('kategori') === $key)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
    <div><label for="merek">Merek</label><input id="merek" name="merek" value="{{ old('merek') }}" required></div>
    <div><label for="nama">Nama produk</label><input id="nama" name="nama" value="{{ old('nama') }}" required></div>
    <div><label for="spesifikasi">Ukuran / spesifikasi</label><input id="spesifikasi" name="spesifikasi" value="{{ old('spesifikasi') }}" placeholder="80/90-14 Tubeless"></div>
    <div><label for="harga">Harga (Rp)</label><input id="harga" name="harga" type="number" min="0" step="1000" value="{{ old('harga') }}" required></div>
    <button class="btn sm" style="padding:.65rem 1rem" type="submit">Tambah produk</button>
  </form>
  @if($errors->any())<p class="err" role="alert">{{ $errors->first() }}</p>@endif

  <div class="scroll">
    <table>
      <thead><tr><th>Kategori</th><th>Produk</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
        @forelse($products as $p)
          <tr>
            <td>{{ $p->kategori_label }}</td>
            <td>{{ $p->merek }} {{ $p->nama }}@if($p->spesifikasi)<br><small>{{ $p->spesifikasi }}</small>@endif</td>
            <td>
              <form method="POST" action="{{ route('admin.produk.update', $p) }}" style="display:flex;gap:.4rem">
                @csrf @method('PATCH')
                <input name="harga" type="number" min="0" step="1000" value="{{ $p->harga }}" style="width:8.5rem" aria-label="Harga {{ $p->merek }} {{ $p->nama }}">
                <button class="btn sm ghost" type="submit">Simpan</button>
              </form>
            </td>
            <td><span class="badge {{ $p->is_active ? 's-Selesai' : 's-Dibatalkan' }}">{{ $p->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
            <td style="text-align:right;white-space:nowrap">
              <form method="POST" action="{{ route('admin.produk.toggle', $p) }}" style="display:inline">
                @csrf @method('PATCH')
                <button class="btn sm ghost" type="submit">{{ $p->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
              </form>
              <a class="btn sm ghost link" href="{{ route('admin.produk.edit', $p) }}">Edit</a>
              <form method="POST" action="{{ route('admin.produk.hapus', $p) }}" style="display:inline" onsubmit="return confirm('Hapus produk ini?')">
                @csrf @method('DELETE')
                <button class="btn sm danger" type="submit">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5">Belum ada produk.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
