@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-heading">
    <div>
        <h1>Dashboard</h1>
        <p>Ringkasan katalog produk, stok, dan pemetaan lokasi UMKM dari database.</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn-green">
        <i class="bi bi-plus-lg"></i> Tambah Produk
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <span class="stat-icon"><i class="bi bi-box"></i></span>
            <span>Total Produk</span>
        </div>
        <strong>{{ number_format($stats['products'], 0, ',', '.') }}</strong>
        <small>Produk terdaftar ({{ number_format($stats['stock_total'], 0, ',', '.') }} pcs stok)</small>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <span class="stat-icon"><i class="bi bi-tags"></i></span>
            <span>Total Kategori</span>
        </div>
        <strong>{{ number_format($stats['categories'], 0, ',', '.') }}</strong>
        <small>Kategori berbeda</small>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <span class="stat-icon"><i class="bi bi-cash-coin"></i></span>
            <span>Rata-rata Harga</span>
        </div>
        <strong>Rp{{ number_format($stats['average_price'], 0, ',', '.') }}</strong>
        <small>Rata-rata harga katalog</small>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <span class="stat-icon"><i class="bi bi-wallet2"></i></span>
            <span>Jumlah Harga</span>
        </div>
        <strong>Rp{{ number_format($stats['price_total'], 0, ',', '.') }}</strong>
        <small>Akumulasi harga katalog produk</small>
    </div>
</div>

<div class="content-grid dashboard-grid">
    <article class="panel chart-panel">
        <div class="panel-heading">
            <h2>Perbandingan Harga Produk Terbaru</h2>
            <span class="subtle">Data aktual</span>
        </div>
        <div class="chart-wrap"><canvas id="productChart"></canvas></div>
    </article>

    <article class="panel chart-panel">
        <div class="panel-heading">
            <h2>Jumlah Produk per Kategori</h2>
        </div>
        <div class="donut-wrap"><canvas id="categoryChart"></canvas></div>
    </article>

    <article class="panel">
        <div class="panel-heading">
            <h2>Produk Terbaru</h2>
            <a class="text-link" href="{{ route('products.index') }}">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="overflow-x-auto">
            <table class="clean-table">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestProducts as $product)
                        <tr>
                            <td>
                                <span class="product-thumb"><i class="bi bi-box-seam"></i></span>
                                <strong>{{ $product->name }}</strong>
                            </td>
                            <td><span class="category-pill">{{ $product->category ?: 'Umum' }}</span></td>
                            <td>{{ number_format((int) $product->stock, 0, ',', '.') }} pcs</td>
                            <td><strong>Rp{{ number_format((float) $product->price, 0, ',', '.') }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">
                                Belum ada produk. <a href="{{ route('products.create') }}" class="text-link">Tambah produk pertama</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>

    <article class="panel map-preview">
        <div class="panel-heading">
            <h2>Peta Lokasi UMKM</h2>
            <a class="text-link" href="{{ route('map.index') }}">Lihat Peta Lengkap <i class="bi bi-arrow-right"></i></a>
        </div>
        <div id="dashboardMap" class="mini-map"></div>
        <p class="map-note"><i class="bi bi-info-circle"></i> Menampilkan titik pemesanan produk UMKM yang saling terhubung.</p>
    </article>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.umkmCharts.dashboard({
        productLabels: @json($priceLabels),
        productValues: @json($priceValues),
        categoryLabels: @json($categoryLabels),
        categoryValues: @json($categoryValues)
    });
    window.umkmMaps.mini('dashboardMap', @json($mapPoints));
});
</script>
@endpush
