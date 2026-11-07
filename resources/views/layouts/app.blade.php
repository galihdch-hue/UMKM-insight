<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'UMKM Insight') — UMKM Insight</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/umkm-insight.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a href="{{ route('umkm.dashboard') }}" class="brand">
            <span class="brand-mark"><i class="bi bi-shop-window"></i></span>
            <span>UMKM<br><b>Insight</b></span>
        </a>
        <div class="store-chip">
            <span class="store-icon"><i class="bi bi-shop"></i></span>
            <div>
                <strong>{{ config('app.umkm_name', 'Bintang Agung UMKM') }}</strong>
                <small>Terverifikasi <i class="bi bi-check-circle-fill"></i></small>
            </div>
        </div>
        <nav class="side-nav" aria-label="Navigasi Utama">
            <a href="{{ route('umkm.dashboard') }}" class="{{ request()->routeIs('umkm.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i>Dashboard
            </a>
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>Produk
            </a>
            <a href="{{ route('map.index') }}" class="{{ request()->routeIs('map.*', 'umkm.map') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i>Peta Lokasi (Leaflet)
            </a>
            <a href="{{ route('umkm.analytics') }}" class="{{ request()->routeIs('umkm.analytics') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i>Laporan Analitik
            </a>
        </nav>
        <div class="side-bottom">
            <a href="{{ route('products.index') }}"><i class="bi bi-gear"></i>Pengaturan</a>
            @if (\Illuminate\Support\Facades\Route::has('logout'))
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>Keluar
                </a>
                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>
            @endif
        </div>
    </aside>

    <main class="main-area">
        <header class="topbar">
            <div class="welcome">
                <small>Selamat Datang Kembali!</small>
                <strong>{{ auth()->user()->name ?? 'Admin UMKM' }}</strong>
            </div>
            <div class="top-actions">
                <form action="{{ route('products.index') }}" method="GET" class="searchbox">
                    <i class="bi bi-search"></i>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari produk, data, atau lainnya..." aria-label="Cari">
                </form>
                <button class="icon-button" type="button" title="Mode gelap" id="themeToggle">
                    <i class="bi bi-moon"></i>
                </button>
                <a href="{{ route('map.index') }}" class="icon-button notice" title="Notifikasi">
                    <i class="bi bi-bell"></i>
                </a>
                <div class="user-chip">
                    <span class="avatar"><i class="bi bi-person"></i></span>
                    <div>
                        <strong>{{ auth()->user()->name ?? 'Admin UMKM' }}</strong>
                        <small>Administrator</small>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </div>
            </div>
        </header>

        <section class="page-content">
            @if(session('success'))
                <div class="mb-6 bg-[#eaf6ec] border border-[#86efac] text-[#054a14] px-4 py-3 rounded-xl flex items-center justify-between shadow-xs" role="alert">
                    <div class="flex items-center gap-2.5">
                        <i class="bi bi-check-circle-fill text-[#087a32]"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </section>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/umkm-insight.js') }}"></script>
@stack('scripts')
</body>
</html>
