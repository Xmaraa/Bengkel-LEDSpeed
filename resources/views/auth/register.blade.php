@extends('layouts.app')
@section('title', 'Daftar')
@section('content')
<div class="card auth">
  <h2>Daftar akun</h2>
  <a class="btn-google" href="{{ route('google.redirect') }}">
    <span>Daftar dengan Google</span>
  </a>
  <div class="or">ATAU</div>
  <form method="POST" action="{{ route('register.proses') }}">
    @csrf
    <label for="name">Nama lengkap</label>
    <input id="name" name="name" value="{{ old('name') }}" required autocomplete="name">
    <label for="email">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email">
    <label for="phone">No. HP</label>
    <input id="phone" name="phone" inputmode="tel" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required autocomplete="tel">
    <label for="password">Password (min. 8 karakter)</label>
    <input id="password" name="password" type="password" required autocomplete="new-password">
    <label for="password_confirmation">Ulangi password</label>
    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
    @if($errors->any())<p class="err" role="alert">{{ $errors->first() }}</p>@endif
    <button class="btn" type="submit">Daftar</button>
  </form>
  <p class="note">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
</div>
@endsection
