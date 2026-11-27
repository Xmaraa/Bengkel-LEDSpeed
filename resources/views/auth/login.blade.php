@extends('layouts.app')
@section('title', 'Masuk')
@section('content')
<div class="card auth">
  <h2>Masuk</h2>
  <a class="btn-google" href="{{ route('google.redirect') }}">
    <span>Lanjutkan dengan Google</span>
  </a>
  <div class="or">ATAU</div>
  <form method="POST" action="{{ route('login.proses') }}">
    @csrf
    <label for="email">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email">
    <label for="password">Password</label>
    <input id="password" name="password" type="password" required autocomplete="current-password">
    <label style="display:flex;gap:.5rem;align-items:center;font-weight:400"><input type="checkbox" name="remember" style="width:auto"> Ingat saya</label>
    @if($errors->any())<p class="err" role="alert">{{ $errors->first() }}</p>@endif
    <button class="btn" type="submit">Masuk</button>
  </form>
  <p class="note">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
</div>
@endsection
