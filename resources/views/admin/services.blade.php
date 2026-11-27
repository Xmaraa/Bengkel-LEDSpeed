@extends('layouts.app')
@section('title', 'Layanan')
@section('content')
<h2>Layanan &amp; harga</h2>
<div class="card" style="margin-top:1rem">
  <form class="row" method="POST" action="{{ route('admin.layanan.simpan') }}" style="margin-top:0">
    @csrf
    <div><label for="nama">Nama layanan</label><input id="nama" name="nama" value="{{ old('nama') }}" required></div>
    <div><label for="harga">Harga (Rp)</label><input id="harga" name="harga" type="number" min="0" step="1000" value="{{ old('harga') }}" required></div>
    <button class="btn sm" style="padding:.65rem 1rem" type="submit">Tambah layanan</button>
  </form>
  @if($errors->any())<p class="err" role="alert">{{ $errors->first() }}</p>@endif
  <div class="scroll">
    <table>
      <tbody>
        @forelse($services as $s)
          <tr>
            <td>{{ $s->nama }}</td>
            <td>Rp{{ number_format($s->harga, 0, ',', '.') }}</td>
            <td><span class="badge {{ $s->is_active ? 's-Selesai' : 's-Dibatalkan' }}">{{ $s->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
            <td style="text-align:right;white-space:nowrap">
              <form method="POST" action="{{ route('admin.layanan.toggle', $s) }}" style="display:inline">
                @csrf @method('PATCH')
                <button class="btn sm ghost" type="submit">{{ $s->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
              </form>
              <form method="POST" action="{{ route('admin.layanan.hapus', $s) }}" style="display:inline" onsubmit="return confirm('Hapus layanan ini?')">
                @csrf @method('DELETE')
                <button class="btn sm danger" type="submit">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td>Belum ada layanan.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
