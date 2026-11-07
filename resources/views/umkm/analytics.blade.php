@extends('layouts.app')

@section('title', 'Analitik Produk')

@section('content')
<div class="page-heading">
    <div>
        <h1>Analitik Produk</h1>
        <p>Analisis katalog, stok, dan harga produk berdasarkan data yang tersedia.</p>
    </div>
    <button class="btn-green" type="button" onclick="window.print()">
        <i class="bi bi-printer"></i> Cetak Laporan
    </button>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <span class="stat-icon"><i class="bi bi-box"></i></span>
            <span>Total Produk</span>
        </div>
        <strong>{{ number_format($stats['products'], 0, ',', '.') }}</strong>
        <small>Produk terdaftar</small>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <span class="stat-icon"><i class="bi bi-tags"></i></span>
            <span>Total Kategori</span>
        </div>
        <strong>{{ number_format($stats['categories'], 0, ',', '.') }}</strong>
        <small>Kategori produk</small>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <span class="stat-icon"><i class="bi bi-cash-coin"></i></span>
            <span>Rata-rata Harga</span>
        </div>
        <strong>Rp{{ number_format($stats['average_price'], 0, ',', '.') }}</strong>
        <small>Harga rata-rata</small>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <span class="stat-icon"><i class="bi bi-graph-up"></i></span>
            <span>Harga Tertinggi</span>
        </div>
        <strong>Rp{{ number_format($stats['highest_price'], 0, ',', '.') }}</strong>
        <small>Produk dengan harga tertinggi</small>
    </div>
</div>

<div class="content-grid analytics-grid">
    <article class="panel">
        <div class="panel-heading"><h2>Perbandingan Harga Produk</h2></div>
        <div class="chart-wrap"><canvas id="salesChart"></canvas></div>
    </article>

    <article class="panel">
        <div class="panel-heading"><h2>Distribusi Kategori</h2></div>
        <div class="donut-wrap"><canvas id="salesCategoryChart"></canvas></div>
    </article>

    <article class="panel">
        <div class="panel-heading"><h2>Daftar Produk Berdasarkan Harga</h2></div>
        <div class="overflow-x-auto">
            <table class="clean-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $i => $product)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td><span class="category-pill">{{ $product->category ?: 'Umum' }}</span></td>
                            <td>{{ number_format((int) $product->stock, 0, ',', '.') }} pcs</td>
                            <td><strong>Rp{{ number_format((float) $product->price, 0, ',', '.') }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">Belum ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>

    <article class="panel">
        <div class="panel-heading"><h2>Ringkasan per Kategori</h2></div>
        <div class="overflow-x-auto">
            <table class="clean-table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Jumlah Produk</th>
                        <th>Total Stok</th>
                        <th>Rata-rata Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorySummary as $row)
                        <tr>
                            <td><span class="category-pill">{{ $row->category }}</span></td>
                            <td>{{ $row->product_count }}</td>
                            <td>{{ number_format((int) $row->total_stock, 0, ',', '.') }} pcs</td>
                            <td><strong>Rp{{ number_format((float) $row->average_price, 0, ',', '.') }}</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty-state">Kategori belum tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.umkmCharts.analytics({
        labels: @json($priceLabels),
        prices: @json($priceValues),
        categories: @json($categoryLabels),
        categoryValues: @json($categoryValues)
    });
});
</script>
@endpush
