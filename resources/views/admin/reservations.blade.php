@extends('layouts.app')
@section('title', 'Admin')
@section('content')
<h2>Panel admin</h2>
<div class="stats">
  @foreach($stats as $label => $n)
    <div class="card"><b>{{ $n }}</b>{{ ucfirst($label) }}</div>
  @endforeach
</div>
<div class="card scroll">
  <table>
    <thead><tr><th>Kode</th><th>Pelanggan</th><th>Kendaraan</th><th>Layanan</th><th>Jadwal</th><th>Status</th><th></th></tr></thead>
    <tbody>
      @forelse($reservations as $r)
        <tr>
          <td>{{ $r->kode }}</td>
          <td>{{ $r->user->name }}<br><small>{{ $r->user->phone }}</small></td>
          <td>{{ ucfirst($r->vehicle->jenis) }}<br><small>{{ $r->vehicle->plat_nomor }}</small></td>
          <td>{{ $r->service->nama }}</td>
          <td>{{ $r->tanggal->format('Y-m-d') }}<br><small>{{ $r->jam }}</small></td>
          <td>
            <form method="POST" action="{{ route('admin.reservasi.status', $r) }}">
              @csrf @method('PATCH')
              <select name="status" aria-label="Status {{ $r->kode }}" onchange="this.form.submit()">
                @foreach($statuses as $s)<option value="{{ $s }}" @selected($r->status === $s)>{{ ucfirst($s) }}</option>@endforeach
              </select>
            </form>
          </td>
          <td>
            <form method="POST" action="{{ route('admin.reservasi.hapus', $r) }}" onsubmit="return confirm('Hapus reservasi ini?')">
              @csrf @method('DELETE')
              <button class="btn sm danger" type="submit">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="7"><div class="empty">Belum ada reservasi masuk.</div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
