@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                Edit Produk <span class="text-[#097220]">P{{ str_pad((string) $product->id, 3, '0', STR_PAD_LEFT) }}</span>
            </h2>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui detail informasi, harga, dan stok barang.</p>
        </div>
        <a href="{{ route('products.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
            <span>&larr; Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-2xs p-6 sm:p-8">
        <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Produk <span class="text-red-600">*</span>
                </label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name', $product->name) }}"
                       required
                       class="w-full px-4 py-2.5 bg-gray-50/60 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#097220] @error('name') border-red-500 @enderror"
                       placeholder="Masukkan nama produk">
                @error('name')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                        Kategori
                    </label>
                    <input type="text"
                           name="category"
                           id="category"
                           list="category-options"
                           value="{{ old('category', $product->category) }}"
                           class="w-full px-4 py-2.5 bg-gray-50/60 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#097220] @error('category') border-red-500 @enderror"
                           placeholder="Makanan / Minuman">
                    <datalist id="category-options">
                        <option value="Makanan"></option>
                        <option value="Minuman"></option>
                        <option value="Kerajinan"></option>
                    </datalist>
                    @error('category')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                        Harga (Rp) <span class="text-red-600">*</span>
                    </label>
                    <input type="number"
                           name="price"
                           id="price"
                           value="{{ old('price', (float) $product->price) }}"
                           step="1"
                           min="0"
                           required
                           class="w-full px-4 py-2.5 bg-gray-50/60 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#097220] @error('price') border-red-500 @enderror"
                           placeholder="15000">
                    @error('price')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">
                        Stok Barang (pcs) <span class="text-red-600">*</span>
                    </label>
                    <input type="number"
                           name="stock"
                           id="stock"
                           value="{{ old('stock', $product->stock) }}"
                           step="1"
                           min="0"
                           required
                           class="w-full px-4 py-2.5 bg-gray-50/60 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#097220] @error('stock') border-red-500 @enderror"
                           placeholder="100">
                    @error('stock')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    Deskripsi Produk
                </label>
                <textarea name="description"
                          id="description"
                          rows="4"
                          class="w-full px-4 py-2.5 bg-gray-50/60 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#097220] @error('description') border-red-500 @enderror"
                          placeholder="Masukkan deskripsi singkat produk...">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2 flex items-center gap-3">
                <button type="submit"
                        class="bg-[#097220] hover:bg-[#075c19] text-white text-sm font-semibold py-2.5 px-6 rounded-xl shadow-xs transition">
                    Update Produk
                </button>
                <a href="{{ route('products.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold py-2.5 px-6 rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
