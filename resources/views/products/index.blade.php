@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="space-y-6">
    <!-- Top Action & Search Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 flex-1">
            <h2 class="text-2xl font-bold text-gray-800 shrink-0">Produk</h2>

            <form action="{{ route('products.index') }}" method="GET" class="relative flex-1 max-w-md">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('stock_status'))
                    <input type="hidden" name="stock_status" value="{{ request('stock_status') }}">
                @endif
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari produk..."
                       aria-label="Cari produk"
                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#097220] shadow-2xs">
            </form>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('products.create') }}"
               class="inline-flex items-center justify-center gap-2 bg-[#097220] hover:bg-[#075c19] text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Produk</span>
            </a>

            <a href="{{ route('umkm.analytics') }}"
               class="inline-flex items-center justify-center gap-2 bg-[#eaf7ec] hover:bg-[#dcfce7] text-[#14532d] border border-[#bbf7d0] text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Lihat Laporan</span>
            </a>
        </div>
    </div>

    <!-- 3 Summary Cards -->
    <div id="ringkasan" class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-[#e6f6e8] text-[#097220] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-600">Total Produk</span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900 mb-3">
                {{ number_format($totalProducts, 0, ',', '.') }}
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-md bg-[#dcfce7] text-[#15803d] font-bold">
                    &#9650; 10%
                </span>
                <span class="text-gray-500">vs Minggu Lalu</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-[#e6f6e8] text-[#097220] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-600">Produk Terjual <span class="text-xs font-normal text-gray-400">(Stok: {{ number_format($totalStock, 0, ',', '.') }} pcs)</span></span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900 mb-3">
                {{ number_format($totalStock, 0, ',', '.') }}
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-md bg-[#dcfce7] text-[#15803d] font-bold">
                    &#9650; 17%
                </span>
                <span class="text-gray-500">vs Minggu Lalu</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-[#e6f6e8] text-[#097220] flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-600">Pendapatan</span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900 mb-3">
                Rp{{ number_format($totalInventoryValue, 0, ',', '.') }}
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-md bg-[#dcfce7] text-[#15803d] font-bold">
                    &#9650; 20%
                </span>
                <span class="text-gray-500">vs Minggu Lalu</span>
            </div>
        </div>
    </div>

    <!-- Product Table Card -->
    <div id="daftar-produk" class="bg-white rounded-2xl border border-gray-100 shadow-2xs">
        <div class="p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-gray-100">
            <h3 class="text-lg font-extrabold text-gray-900">Daftar Produk</h3>

            <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <select name="category"
                        aria-label="Filter Kategori"
                        onchange="this.form.submit()"
                        class="px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#097220]">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>

                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-600">
                    <span class="font-medium">Rp10.000</span>
                    <input type="range"
                           name="max_price"
                           min="10000"
                           max="200000"
                           step="5000"
                           value="{{ request('max_price', 200000) }}"
                           aria-label="Batas harga maksimum"
                           onchange="this.form.submit()"
                           class="w-20 accent-[#097220] cursor-pointer">
                    <span class="font-semibold text-gray-800">Rp{{ number_format((float) request('max_price', 200000), 0, ',', '.') }}</span>
                </div>

                <select name="stock_status"
                        aria-label="Filter Stok"
                        onchange="this.form.submit()"
                        class="px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#097220]">
                    <option value="">Semua Stok</option>
                    <option value="available" @selected(request('stock_status') === 'available')>Stok Aman (&gt;20 pcs)</option>
                    <option value="low" @selected(request('stock_status') === 'low')>Stok Menipis (1-20 pcs)</option>
                    <option value="out" @selected(request('stock_status') === 'out')>Stok Habis (0 pcs)</option>
                </select>

                @if(request()->anyFilled(['search', 'category', 'max_price', 'stock_status']))
                    <a href="{{ route('products.index') }}"
                       class="px-3 py-2 text-xs font-semibold text-[#097220] hover:underline">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-[#eaf6ec]">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-700">Nama Produk</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-700">ID Produk</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-700">Harga</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-700">Stok</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-700">Kategori</th>
                        <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($products as $product)
                        @php
                            $isDrink = str_contains(strtolower((string) $product->category), 'minum');
                        @endphp
                        <tr class="hover:bg-[#e7fae6]/65 transition group">
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $isDrink ? 'bg-amber-50 text-amber-700 border border-amber-200/60' : 'bg-[#eaf6ec] text-[#097220] border border-[#bbf7d0]' }}">
                                        @if($isDrink)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zm4-4h8" />
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-800">{{ $product->name }}</div>
                                        @if($product->description)
                                            <div class="text-xs text-gray-400 truncate max-w-xs">{{ $product->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-3.5 whitespace-nowrap text-sm font-medium text-gray-500">
                                P{{ str_pad((string) $product->id, 3, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="px-6 py-3.5 whitespace-nowrap text-sm font-bold text-gray-800">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-3.5 whitespace-nowrap text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold {{ $product->stock <= 20 ? 'text-red-600' : 'text-gray-800' }}">
                                        {{ number_format($product->stock, 0, ',', '.') }} pcs
                                    </span>
                                    @if($product->stock <= 20)
                                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block" title="Stok menipis"></span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-[#e2f5e4] text-[#166534]">
                                    {{ $product->category ?: 'Umum' }}
                                </span>
                            </td>

                            <td class="px-6 py-3.5 whitespace-nowrap text-center relative">
                                <div class="inline-block text-left" data-dropdown>
                                    <button type="button"
                                            data-dropdown-btn
                                            aria-haspopup="true"
                                            aria-expanded="false"
                                            aria-label="Menu aksi untuk {{ $product->name }}"
                                            class="p-2 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#097220]">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zm6 0a2 2 0 11-4 0 2 2 0 014 0zm4 2a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>

                                    <div data-dropdown-menu
                                         class="hidden origin-top-right absolute right-6 mt-1 w-36 rounded-xl bg-white shadow-lg border border-gray-100 py-1.5 z-30">
                                        <a href="{{ route('products.edit', $product) }}"
                                           class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-gray-700 hover:bg-[#eaf6ec] hover:text-[#097220] transition">
                                            <svg class="w-4 h-4 text-[#097220]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Edit</span>
                                        </a>

                                        <form action="{{ route('products.destroy', $product) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus produk {{ addslashes($product->name) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                Belum ada data produk yang sesuai.
                                <a href="{{ route('products.create') }}" class="text-[#097220] font-semibold hover:underline ml-1">
                                    Tambah produk baru
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdowns = document.querySelectorAll('[data-dropdown]');

        function closeAllDropdowns() {
            dropdowns.forEach(function (dd) {
                const menu = dd.querySelector('[data-dropdown-menu]');
                const btn = dd.querySelector('[data-dropdown-btn]');
                if (menu) menu.classList.add('hidden');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            });
        }

        dropdowns.forEach(function (dd) {
            const btn = dd.querySelector('[data-dropdown-btn]');
            const menu = dd.querySelector('[data-dropdown-menu]');

            if (btn && menu) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isHidden = menu.classList.contains('hidden');
                    closeAllDropdowns();
                    if (isHidden) {
                        menu.classList.remove('hidden');
                        btn.setAttribute('aria-expanded', 'true');
                    }
                });
            }
        });

        document.addEventListener('click', closeAllDropdowns);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAllDropdowns();
            }
        });
    });
</script>
@endsection
