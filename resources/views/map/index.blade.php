@extends('layouts.app')

@section('title', 'Peta Lokasi Produk')

@push('styles')
    <style>
        .leaflet-popup-content-wrapper {
            border-radius: 16px;
            padding: 2px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12);
        }
        .leaflet-popup-content {
            margin: 12px 14px;
            line-height: 1.45;
        }
    </style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Top Action & Filter Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 flex-1">
            <h2 class="text-2xl font-bold text-gray-800 shrink-0">Peta Lokasi (Leaflet)</h2>

            <form action="{{ route('map.index') }}" method="GET" class="flex flex-wrap sm:flex-nowrap items-center gap-2.5 flex-1 max-w-xl">
                <div class="relative flex-1 min-w-[200px]">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari produk, pemesan, atau lokasi..."
                           aria-label="Cari titik lokasi produk"
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#097220] shadow-2xs">
                </div>

                <select name="product_id"
                        aria-label="Filter Produk"
                        onchange="this.form.submit()"
                        class="px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#097220] shadow-2xs">
                    <option value="">Semua Produk</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" @selected((string) request('product_id') === (string) $prod->id)>
                            P{{ str_pad((string) $prod->id, 3, '0', STR_PAD_LEFT) }} - {{ $prod->name }}
                        </option>
                    @endforeach
                </select>

                @if(request()->anyFilled(['search', 'product_id']))
                    <a href="{{ route('map.index') }}"
                       class="px-3 py-2 text-xs font-semibold text-[#097220] hover:underline shrink-0">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="flex items-center gap-3">
            <a href="#form-lokasi"
               onclick="document.getElementById('product_id')?.focus()"
               class="inline-flex items-center justify-center gap-2 bg-[#097220] hover:bg-[#075c19] text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Titik Lokasi</span>
            </a>

            <a href="{{ route('products.index') }}"
               class="inline-flex items-center justify-center gap-2 bg-[#eaf7ec] hover:bg-[#dcfce7] text-[#14532d] border border-[#bbf7d0] text-sm font-semibold px-4 py-2.5 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span>Data Produk</span>
            </a>
        </div>
    </div>

    <!-- 3 Summary Cards -->
    <div id="ringkasan-peta" class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-[#e6f6e8] text-[#097220] flex items-center justify-center shrink-0">
                    <i class="bi bi-geo-alt text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-gray-600">Total Titik Lokasi</span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900 mb-3">
                {{ number_format($totalLocations, 0, ',', '.') }}
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-md bg-[#dcfce7] text-[#15803d] font-bold">
                    Aktif
                </span>
                <span class="text-gray-500">Titik sebaran pemesanan UMKM</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-[#e6f6e8] text-[#097220] flex items-center justify-center shrink-0">
                    <i class="bi bi-box-seam text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-gray-600">Produk Terpetakan</span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900 mb-3">
                {{ number_format($mappedProductsCount, 0, ',', '.') }}
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-md bg-[#dcfce7] text-[#15803d] font-bold">
                    Terhubung
                </span>
                <span class="text-gray-500">Memiliki nama &amp; informasi produk di peta</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-[#e6f6e8] text-[#097220] flex items-center justify-center shrink-0">
                    <i class="bi bi-cart-check text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-gray-600">Total Unit Pesanan</span>
            </div>
            <div class="text-3xl font-extrabold text-gray-900 mb-3">
                {{ number_format($totalOrderedQty, 0, ',', '.') }} <span class="text-lg font-bold text-gray-500">pcs</span>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-md bg-[#dcfce7] text-[#15803d] font-bold">
                    Tercatat
                </span>
                <span class="text-gray-500">Akumulasi pesanan seluruh titik</span>
            </div>
        </div>
    </div>

    <!-- Main Map + Input Form Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Interactive Leaflet Map Card -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-gray-100 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h3 class="text-lg font-extrabold text-gray-900">Peta Sebaran Pesanan &amp; Informasi Produk</h3>
                        <span id="totalDistanceBadge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#dcfce7] text-[#15803d] text-xs font-bold">
                            Terhubung: 0 km
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Tiap titik terhubung dengan garis rute distribusi. Klik marker atau garis penghubung untuk melihat detail produk &amp; jarak.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    <select id="connectionModeSelect"
                            aria-label="Mode Hubungan Titik"
                            class="px-3 py-1.5 rounded-xl bg-gray-50 border border-gray-200 text-xs font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#097220]">
                        <option value="route">Garis Rute Berurutan</option>
                        <option value="loop">Garis Rute Melingkar (Loop)</option>
                        <option value="network">Hubungkan Semua Titik (Jaringan)</option>
                        <option value="none">Sembunyikan Garis</option>
                    </select>

                    <button type="button"
                            id="resetMapBoundsBtn"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#eaf6ec] hover:bg-[#dcfce7] text-[#097220] text-xs font-bold transition cursor-pointer">
                        <i class="bi bi-arrows-fullscreen"></i>
                        <span>Lihat Semua Titik</span>
                    </button>
                </div>
            </div>

            <div id="umkmMap" class="w-full h-[480px] z-10 bg-[#eef6f0]"></div>
        </div>

        <!-- Add Location Form Card -->
        <div id="form-lokasi" class="lg:col-span-4 bg-white rounded-2xl border border-gray-100 shadow-2xs p-5 sm:p-6">
            <div class="mb-4">
                <span class="inline-block px-2.5 py-1 rounded-lg bg-[#eaf6ec] text-[#097220] text-xs font-bold mb-1.5">
                    Input Titik Peta
                </span>
                <h3 class="text-lg font-extrabold text-gray-900">Tambah Lokasi Produk</h3>
                <p class="text-xs text-gray-500 mt-0.5">Pilih produk UMKM dan tentukan titik koordinat pemesan.</p>
            </div>

            <form action="{{ route('map.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="product_id" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Pilih Produk UMKM <span class="text-red-600">*</span>
                    </label>
                    <select name="product_id"
                            id="product_id"
                            required
                            class="w-full px-3.5 py-2.5 bg-gray-50/70 border border-gray-200 rounded-xl text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#097220] @error('product_id') border-red-500 @enderror">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}"
                                    data-code="P{{ str_pad((string) $prod->id, 3, '0', STR_PAD_LEFT) }}"
                                    data-name="{{ $prod->name }}"
                                    data-category="{{ $prod->category ?: 'Umum' }}"
                                    data-price="Rp{{ number_format((float) $prod->price, 0, ',', '.') }}"
                                    data-stock="{{ number_format((int) $prod->stock, 0, ',', '.') }} pcs"
                                    data-desc="{{ $prod->description ?: 'Produk unggulan UMKM.' }}"
                                    @selected(old('product_id') == $prod->id)>
                                P{{ str_pad((string) $prod->id, 3, '0', STR_PAD_LEFT) }} - {{ $prod->name }} (Rp{{ number_format((float) $prod->price, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Live Product Info Preview Card -->
                    <div id="selectedProductPreview" class="hidden mt-2.5 p-3 rounded-xl bg-[#f3f9f4] border border-[#bbf7d0] text-xs space-y-1">
                        <div class="flex items-center justify-between">
                            <span id="previewName" class="font-extrabold text-[#054a14]"></span>
                            <span id="previewCategory" class="px-2 py-0.5 rounded-md bg-[#dcfce7] text-[#166534] font-bold text-[11px]"></span>
                        </div>
                        <p id="previewDesc" class="text-gray-600 line-clamp-2"></p>
                        <div class="flex items-center justify-between pt-1 border-t border-[#bbf7d0]/60 text-gray-700 font-semibold">
                            <span>Harga: <strong id="previewPrice" class="text-gray-900"></strong></span>
                            <span>Stok: <strong id="previewStock" class="text-[#097220]"></strong></span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <label for="customer_name" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Nama Pemesan / Mitra
                        </label>
                        <input type="text"
                               name="customer_name"
                               id="customer_name"
                               value="{{ old('customer_name') }}"
                               placeholder="Contoh: Toko Berkah Jaya"
                               class="w-full px-3.5 py-2 bg-gray-50/70 border border-gray-200 rounded-xl text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#097220]">
                    </div>

                    <div>
                        <label for="quantity" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Jumlah (pcs) <span class="text-red-600">*</span>
                        </label>
                        <input type="number"
                               name="quantity"
                               id="quantity"
                               value="{{ old('quantity', 10) }}"
                               min="1"
                               required
                               class="w-full px-3.5 py-2 bg-gray-50/70 border border-gray-200 rounded-xl text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#097220]">
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Detail Alamat / Keterangan Lokasi
                    </label>
                    <input type="text"
                           name="address"
                           id="address"
                           value="{{ old('address') }}"
                           placeholder="Contoh: Jl. Sudirman No. 10, Bandung"
                           class="w-full px-3.5 py-2 bg-gray-50/70 border border-gray-200 rounded-xl text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#097220]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="latitude" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Latitude <span class="text-red-600">*</span>
                        </label>
                        <input type="number"
                               step="any"
                               name="latitude"
                               id="latitude"
                               value="{{ old('latitude', '-6.2000000') }}"
                               required
                               class="w-full px-3.5 py-2 bg-gray-50/70 border border-gray-200 rounded-xl text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#097220] @error('latitude') border-red-500 @enderror">
                    </div>

                    <div>
                        <label for="longitude" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Longitude <span class="text-red-600">*</span>
                        </label>
                        <input type="number"
                               step="any"
                               name="longitude"
                               id="longitude"
                               value="{{ old('longitude', '106.8166660') }}"
                               required
                               class="w-full px-3.5 py-2 bg-gray-50/70 border border-gray-200 rounded-xl text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#097220] @error('longitude') border-red-500 @enderror">
                    </div>
                </div>

                <!-- Quick City Coordinate Presets -->
                <div>
                    <span class="block text-[11px] font-semibold text-gray-400 mb-1.5">Pilih Cepat Kota:</span>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" data-lat="-6.1944491" data-lng="106.8229198" class="city-preset px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-[#eaf6ec] hover:text-[#097220] text-xs font-semibold text-gray-600 transition cursor-pointer">Jakarta</button>
                        <button type="button" data-lat="-6.9215529" data-lng="107.6110212" class="city-preset px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-[#eaf6ec] hover:text-[#097220] text-xs font-semibold text-gray-600 transition cursor-pointer">Bandung</button>
                        <button type="button" data-lat="-7.7925764" data-lng="110.3658430" class="city-preset px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-[#eaf6ec] hover:text-[#097220] text-xs font-semibold text-gray-600 transition cursor-pointer">Yogyakarta</button>
                        <button type="button" data-lat="-7.2574719" data-lng="112.7520883" class="city-preset px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-[#eaf6ec] hover:text-[#097220] text-xs font-semibold text-gray-600 transition cursor-pointer">Surabaya</button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-[#097220] hover:bg-[#075c19] text-white text-sm font-semibold py-2.5 px-4 rounded-xl shadow-xs transition cursor-pointer">
                    Simpan Titik ke Peta
                </button>
            </form>
        </div>
    </div>

    <!-- Mapped Product Locations Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-extrabold text-gray-900">Daftar Titik Lokasi &amp; Informasi Produk</h3>
                <p class="text-xs text-gray-500 mt-0.5">Klik tombol <strong>Fokus Peta</strong> untuk menyorot marker dan membuka popup informasi produk.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-[#eaf6ec]">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-700">Nama Produk &amp; Informasi Produk</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-700">Kategori</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-700">Pemesan &amp; Lokasi</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-700">Pesanan</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-700">Koordinat</th>
                        <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($locations as $loc)
                        @if($loc->product)
                            <tr class="hover:bg-[#e7fae6]/65 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-[#eaf6ec] text-[#097220] border border-[#bbf7d0] flex items-center justify-center shrink-0 mt-0.5">
                                            <i class="bi bi-box-seam text-base"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-bold text-gray-900">{{ $loc->product->name }}</span>
                                                <span class="text-xs font-semibold text-gray-400">P{{ str_pad((string) $loc->product->id, 3, '0', STR_PAD_LEFT) }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-0.5 max-w-md">
                                                {{ $loc->product->description ?: 'Produk unggulan UMKM.' }}
                                            </p>
                                            <div class="flex items-center gap-3 mt-1 text-xs">
                                                <span class="font-bold text-[#097220]">Rp{{ number_format((float) $loc->product->price, 0, ',', '.') }}</span>
                                                <span class="text-gray-400">&bull;</span>
                                                <span class="text-gray-600">Stok Gudang: <strong>{{ number_format((int) $loc->product->stock, 0, ',', '.') }} pcs</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-[#e2f5e4] text-[#166534]">
                                        {{ $loc->product->category ?: 'Umum' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-800">{{ $loc->customer_name ?: 'Pelanggan UMKM' }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $loc->address ?: 'Koordinat peta' }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-800">{{ number_format((int) $loc->quantity, 0, ',', '.') }} pcs</span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-xs font-mono text-gray-500">
                                    {{ number_format((float) $loc->latitude, 5, '.', '') }}, {{ number_format((float) $loc->longitude, 5, '.', '') }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <button type="button"
                                                data-focus-marker="{{ $loc->id }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#eaf6ec] hover:bg-[#097220] text-[#097220] hover:text-white text-xs font-bold transition cursor-pointer">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            <span>Fokus Peta</span>
                                        </button>

                                        <form action="{{ route('map.destroy', $loc) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus titik lokasi pesanan ini dari peta?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-2.5 py-1.5 rounded-xl bg-red-50 hover:bg-red-600 text-red-600 hover:text-white text-xs font-bold transition cursor-pointer"
                                                    title="Hapus Titik">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                Belum ada titik lokasi pemesanan produk. Silakan tambahkan melalui panel di samping peta.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const markersData = @json($mapMarkers);
            const map = L.map('umkmMap').setView([-6.9174639, 109.5], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            function createNumberedPinIcon(number) {
                return L.divIcon({
                    className: 'custom-umkm-pin',
                    html: `<div style="position:relative;width:36px;height:36px;border-radius:9999px;background:#097220;border:3px solid #ffffff;box-shadow:0 4px 10px rgba(0,0,0,0.25);display:flex;align-items:center;justify-content:center;color:#ffffff;">
                        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span style="position:absolute;top:-6px;right:-6px;background:#15803d;color:#fff;border:2px solid #fff;font-size:10px;font-weight:800;min-width:18px;height:18px;border-radius:9999px;display:flex;align-items:center;justify-content:center;padding:0 3px;">
                            ${number}
                        </span>
                    </div>`,
                    iconSize: [36, 36],
                    iconAnchor: [18, 18],
                    popupAnchor: [0, -16]
                });
            }

            const markerInstances = {};
            const orderedMarkers = [];
            const bounds = [];
            const connectionLayerGroup = L.layerGroup().addTo(map);

            function escapeHtml(str) {
                return String(str ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            // Sort points west-to-east by longitude for a natural connected chain
            const sortedMarkersData = [...markersData].sort((a, b) => a.longitude - b.longitude);

            window.focusConnectedMarker = function (idx) {
                const target = orderedMarkers[idx];
                if (target) {
                    map.flyTo(target.getLatLng(), 11, { duration: 0.8 });
                    target.openPopup();
                }
            };

            sortedMarkersData.forEach(function (item, idx) {
                const prevIdx = (idx - 1 + sortedMarkersData.length) % sortedMarkersData.length;
                const nextIdx = (idx + 1) % sortedMarkersData.length;
                const navButtonsHtml = sortedMarkersData.length > 1 ? `
                    <div style="display:flex;justify-content:space-between;gap:6px;margin-top:8px;padding-top:8px;border-top:1px solid #e5e7eb;">
                        <button type="button" onclick="window.focusConnectedMarker(${prevIdx})" style="flex:1;background:#f3f9f4;color:#097220;border:1px solid #bbf7d0;border-radius:8px;padding:4px 8px;font-size:11px;font-weight:700;cursor:pointer;">
                            &larr; Titik #${prevIdx + 1}
                        </button>
                        <button type="button" onclick="window.focusConnectedMarker(${nextIdx})" style="flex:1;background:#097220;color:#ffffff;border:none;border-radius:8px;padding:4px 8px;font-size:11px;font-weight:700;cursor:pointer;">
                            Titik #${nextIdx + 1} &rarr;
                        </button>
                    </div>
                ` : '';

                const popupHtml = `
                    <div style="min-width:245px;max-width:285px;font-family: inherit;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:6px;">
                            <span style="background:#eaf6ec;color:#097220;font-weight:700;font-size:11px;padding:2px 8px;border-radius:6px;">
                                Titik #${idx + 1} &bull; ${escapeHtml(item.product_code)} &bull; ${escapeHtml(item.product_category)}
                            </span>
                            <span style="font-size:11px;font-weight:700;color:#15803d;">
                                ${escapeHtml(item.quantity)} pcs
                            </span>
                        </div>

                        <div style="font-size:15px;font-weight:800;color:#111827;margin-bottom:4px;">
                            ${escapeHtml(item.product_name)}
                        </div>

                        <div style="font-size:12px;color:#4b5563;margin-bottom:8px;background:#f9fafb;padding:7px 9px;border-radius:8px;border:1px solid #f3f4f6;">
                            <div style="font-weight:700;color:#374151;margin-bottom:2px;">Informasi Produk:</div>
                            <div style="margin-bottom:4px;">${escapeHtml(item.product_description)}</div>
                            <div style="display:flex;justify-content:space-between;font-weight:700;color:#054a14;border-top:1px dashed #e5e7eb;padding-top:4px;">
                                <span>Harga: ${escapeHtml(item.product_price_formatted)}</span>
                                <span>Stok: ${escapeHtml(item.product_stock)}</span>
                            </div>
                        </div>

                        <div style="font-size:12px;color:#374151;">
                            <div><strong>Pemesan:</strong> ${escapeHtml(item.customer_name)}</div>
                            <div style="color:#6b7280;margin-top:2px;">${escapeHtml(item.address)}</div>
                        </div>
                        ${navButtonsHtml}
                    </div>
                `;

                const marker = L.marker([item.latitude, item.longitude], { icon: createNumberedPinIcon(idx + 1) })
                    .addTo(map)
                    .bindPopup(popupHtml);

                markerInstances[item.id] = marker;
                orderedMarkers.push(marker);
                bounds.push([item.latitude, item.longitude]);
            });

            function drawConnections(mode) {
                connectionLayerGroup.clearLayers();
                const distanceBadge = document.getElementById('totalDistanceBadge');

                if (mode === 'none' || sortedMarkersData.length < 2) {
                    if (distanceBadge) {
                        distanceBadge.textContent = sortedMarkersData.length < 2 ? 'Minimal 2 titik untuk terhubung' : 'Garis Disembunyikan';
                    }
                    return;
                }

                let totalMeters = 0;
                const pairs = [];

                if (mode === 'route' || mode === 'loop') {
                    for (let i = 0; i < sortedMarkersData.length - 1; i++) {
                        pairs.push([sortedMarkersData[i], sortedMarkersData[i + 1], i + 1, i + 2]);
                    }
                    if (mode === 'loop' && sortedMarkersData.length > 2) {
                        pairs.push([
                            sortedMarkersData[sortedMarkersData.length - 1],
                            sortedMarkersData[0],
                            sortedMarkersData.length,
                            1
                        ]);
                    }
                } else if (mode === 'network') {
                    for (let i = 0; i < sortedMarkersData.length; i++) {
                        for (let j = i + 1; j < sortedMarkersData.length; j++) {
                            pairs.push([sortedMarkersData[i], sortedMarkersData[j], i + 1, j + 1]);
                        }
                    }
                }

                pairs.forEach(function ([fromItem, toItem, fromNum, toNum]) {
                    const p1 = L.latLng(fromItem.latitude, fromItem.longitude);
                    const p2 = L.latLng(toItem.latitude, toItem.longitude);
                    const distKm = (p1.distanceTo(p2) / 1000).toFixed(1);
                    totalMeters += p1.distanceTo(p2);

                    L.polyline([p1, p2], {
                        color: '#86efac',
                        weight: 7,
                        opacity: 0.55,
                        lineCap: 'round'
                    }).addTo(connectionLayerGroup);

                    const segmentLine = L.polyline([p1, p2], {
                        color: '#097220',
                        weight: 3,
                        opacity: 0.9,
                        dashArray: mode === 'network' ? '4, 8' : '8, 8',
                        lineCap: 'round'
                    }).addTo(connectionLayerGroup);

                    segmentLine.bindPopup(`
                        <div style="font-size:12px;min-width:210px;">
                            <div style="font-weight:800;color:#054a14;margin-bottom:4px;">
                                Jalur Terhubung (#${fromNum} &harr; #${toNum})
                            </div>
                            <div style="margin-bottom:3px;">
                                <strong>Dari:</strong> ${escapeHtml(fromItem.product_name)} (${escapeHtml(fromItem.customer_name)})
                            </div>
                            <div style="margin-bottom:6px;">
                                <strong>Ke:</strong> ${escapeHtml(toItem.product_name)} (${escapeHtml(toItem.customer_name)})
                            </div>
                            <div style="background:#eaf6ec;color:#097220;padding:4px 8px;border-radius:6px;font-weight:700;text-align:center;">
                                Jarak Udara: ${distKm} km
                            </div>
                        </div>
                    `);

                    if (mode !== 'network') {
                        const midLat = (fromItem.latitude + toItem.latitude) / 2;
                        const midLng = (fromItem.longitude + toItem.longitude) / 2;
                        const distIcon = L.divIcon({
                            className: 'umkm-dist-label',
                            html: `<div style="background:#ffffff;color:#054a14;border:1.5px solid #097220;border-radius:9999px;padding:1px 7px;font-size:10px;font-weight:800;white-space:nowrap;box-shadow:0 2px 6px rgba(0,0,0,0.12);">
                                ${distKm} km
                            </div>`,
                            iconSize: [54, 20],
                            iconAnchor: [27, 10]
                        });
                        L.marker([midLat, midLng], { icon: distIcon, interactive: false }).addTo(connectionLayerGroup);
                    }
                });

                if (distanceBadge) {
                    const totalKm = (totalMeters / 1000).toFixed(1);
                    distanceBadge.textContent = `Terhubung (${pairs.length} Jalur): ${totalKm} km`;
                }
            }

            const modeSelect = document.getElementById('connectionModeSelect');
            drawConnections(modeSelect ? modeSelect.value : 'route');

            if (modeSelect) {
                modeSelect.addEventListener('change', function () {
                    drawConnections(this.value);
                });
            }

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [45, 45], maxZoom: 12 });
            }

            const resetBtn = document.getElementById('resetMapBoundsBtn');
            if (resetBtn) {
                resetBtn.addEventListener('click', function () {
                    if (bounds.length > 0) {
                        map.fitBounds(bounds, { padding: [45, 45], maxZoom: 12 });
                    } else {
                        map.setView([-6.9174639, 109.5], 6);
                    }
                });
            }

            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            let pickerMarker = null;

            function setPickedCoordinates(lat, lng, fly = false) {
                const fixedLat = Number(lat).toFixed(7);
                const fixedLng = Number(lng).toFixed(7);
                if (latInput) latInput.value = fixedLat;
                if (lngInput) lngInput.value = fixedLng;

                if (pickerMarker) {
                    pickerMarker.setLatLng([lat, lng]);
                } else {
                    pickerMarker = L.circleMarker([lat, lng], {
                        radius: 9,
                        color: '#054a14',
                        weight: 3,
                        fillColor: '#22c55e',
                        fillOpacity: 0.9
                    }).addTo(map);
                }

                pickerMarker.bindPopup(`<strong>Titik Dipilih:</strong><br>${fixedLat}, ${fixedLng}`).openPopup();

                if (fly) {
                    map.flyTo([lat, lng], 12, { duration: 0.8 });
                }
            }

            map.on('click', function (e) {
                setPickedCoordinates(e.latlng.lat, e.latlng.lng, false);
            });

            document.querySelectorAll('.city-preset').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const lat = parseFloat(this.getAttribute('data-lat'));
                    const lng = parseFloat(this.getAttribute('data-lng'));
                    setPickedCoordinates(lat, lng, true);
                });
            });

            document.querySelectorAll('[data-focus-marker]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-focus-marker');
                    const marker = markerInstances[id];
                    if (marker) {
                        const latLng = marker.getLatLng();
                        map.flyTo(latLng, 13, { duration: 0.9 });
                        marker.openPopup();
                        document.getElementById('umkmMap')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            });

            const productSelect = document.getElementById('product_id');
            const previewBox = document.getElementById('selectedProductPreview');
            const previewName = document.getElementById('previewName');
            const previewCategory = document.getElementById('previewCategory');
            const previewDesc = document.getElementById('previewDesc');
            const previewPrice = document.getElementById('previewPrice');
            const previewStock = document.getElementById('previewStock');

            function updateProductPreview() {
                if (!productSelect || !previewBox) return;
                const option = productSelect.options[productSelect.selectedIndex];
                if (!option || !option.value) {
                    previewBox.classList.add('hidden');
                    return;
                }
                previewName.textContent = option.getAttribute('data-code') + ' - ' + option.getAttribute('data-name');
                previewCategory.textContent = option.getAttribute('data-category');
                previewDesc.textContent = option.getAttribute('data-desc');
                previewPrice.textContent = option.getAttribute('data-price');
                previewStock.textContent = option.getAttribute('data-stock');
                previewBox.classList.remove('hidden');
            }

            if (productSelect) {
                productSelect.addEventListener('change', updateProductPreview);
                updateProductPreview();
            }
        });
    </script>
@endpush
