<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Reservasi') – LED Speed #9thGarage</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,600;0,700;1,600;1,700&family=Barlow:wght@400;500;600&display=swap">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<header class="top"><div class="bar">
  <a class="brand" href="{{ route('home') }}"><img src="{{ asset('assets/logo.jpg') }}" alt="LED Speed #9thGarage" width="686" height="176"></a>
  <nav aria-label="Menu utama">
    <a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Beranda</a>
    <a href="{{ route('produk') }}" @if(request()->routeIs('produk')) aria-current="page" @endif>Produk &amp; Harga</a>
    @auth
      <a href="{{ route('reservasi.create') }}" @if(request()->routeIs('reservasi.*')) aria-current="page" @endif>Reservasi</a>
      @unless(auth()->user()->isAdmin())
      <a href="{{ route('riwayat') }}" @if(request()->routeIs('riwayat*')) aria-current="page" @endif>Riwayat</a>
      @endunless
      @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.reservasi') }}" @if(request()->routeIs('admin.reservasi*')) aria-current="page" @endif>Admin</a>
        <a href="{{ route('admin.layanan') }}" @if(request()->routeIs('admin.layanan*')) aria-current="page" @endif>Layanan</a>
        <a href="{{ route('admin.produk') }}" @if(request()->routeIs('admin.produk*')) aria-current="page" @endif>Kelola Produk</a>
      @endif
      <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Keluar ({{ \Illuminate\Support\Str::limit(auth()->user()->name, 14) }})</button></form>
    @else
      <a href="{{ route('login') }}" @if(request()->routeIs('login')) aria-current="page" @endif>Masuk</a>
      <a href="{{ route('register') }}" @if(request()->routeIs('register')) aria-current="page" @endif>Daftar</a>
    @endauth
  </nav>
</div></header>

<main>
  @if(session('ok'))<div class="flash ok" role="status">{{ session('ok') }}</div>@endif
  @if(session('error'))<div class="flash bad" role="alert">{{ session('error') }}</div>@endif
  @yield('content')
</main>
@stack('scripts')
</body>
</html>
