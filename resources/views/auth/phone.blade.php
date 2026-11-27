@extends('layouts.app')
@section('title', 'Lengkapi Data')
@section('content')
<div class="card auth">
  <h2>Lengkapi data</h2>
  <p class="note">Halo, <b>{{ auth()->user()->name }}</b>. Isi nomor HP agar bengkel bisa menghubungi Anda terkait reservasi.</p>
  <form method="POST" action="{{ route('phone.simpan') }}">
    @csrf
    <label for="phone">No. HP</label>
    <input id="phone" name="phone" inputmode="tel" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required autofocus autocomplete="tel">
    @if($errors->any())<p class="err" role="alert">{{ $errors->first() }}</p>@endif
    <button class="btn" type="submit">Simpan &amp; lanjutkan</button>
  </form>
</div>
@endsection