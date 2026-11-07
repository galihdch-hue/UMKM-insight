<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Admin — UMKM Insight</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/umkm-insight.css') }}" rel="stylesheet">
</head>
<body class="bg-[#eef6f0] text-gray-800 antialiased min-h-screen flex items-center justify-center p-4 sm:p-6">
    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden grid grid-cols-1 md:grid-cols-12">
        <!-- Left Brand Panel -->
        <div class="md:col-span-5 bg-[#087a32] text-white p-8 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center text-white text-2xl border border-white/20">
                        <i class="bi bi-shop-window"></i>
                    </span>
                    <div class="leading-tight">
                        <span class="block text-lg font-extrabold tracking-tight">UMKM</span>
                        <b class="block text-2xl font-extrabold text-[#bbf7d0] tracking-tight">Insight</b>
                    </div>
                </div>

                <div class="flex items-center gap-3 bg-white/10 border border-white/15 rounded-2xl p-3 mb-6">
                    <span class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white text-lg shrink-0">
                        <i class="bi bi-shop"></i>
                    </span>
                    <div class="leading-tight">
                        <strong class="block text-xs font-bold text-white">{{ config('app.umkm_name', 'Bintang Agung UMKM') }}</strong>
                        <small class="text-[11px] text-[#bbf7d0]">Terverifikasi <i class="bi bi-check-circle-fill"></i></small>
                    </div>
                </div>

                <h1 class="text-2xl font-extrabold leading-snug mb-3">
                    Registrasi Pengelola UMKM Baru
                </h1>
                <p class="text-sm text-[#dcfce7]/90 leading-relaxed">
                    Buat akun administrator untuk mengakses dasbor manajemen produk dan peta lokasi UMKM Insight.
                </p>
            </div>

            <div class="mt-8 pt-6 border-t border-white/15 text-xs text-[#dcfce7]">
                Sudah memiliki akun? Masuk melalui halaman login admin.
            </div>
        </div>

        <!-- Right Register Form Panel -->
        <div class="md:col-span-7 p-7 sm:p-10 flex flex-col justify-center">
            <div class="mb-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-[#087a32] mb-1">Pendaftaran Admin</p>
                <h2 class="text-2xl font-extrabold text-gray-900">Buat Akun Baru</h2>
                <p class="text-sm text-gray-500 mt-1">Isi data di bawah untuk mendaftarkan akun Admin UMKM.</p>
            </div>

            <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Nama Lengkap Admin
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           required
                           placeholder="Contoh: Admin UMKM"
                           class="w-full px-4 py-2.5 bg-gray-50/70 border border-gray-200 rounded-xl text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#087a32] @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Alamat Email
                    </label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email') }}"
                           required
                           placeholder="nama@umkm.com"
                           class="w-full px-4 py-2.5 bg-gray-50/70 border border-gray-200 rounded-xl text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#087a32] @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Kata Sandi
                        </label>
                        <input type="password"
                               name="password"
                               id="password"
                               value="password123"
                               required
                               placeholder="Minimal 6 karakter"
                               class="w-full px-4 py-2.5 bg-gray-50/70 border border-gray-200 rounded-xl text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#087a32] @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-700 mb-1.5">
                            Konfirmasi Kata Sandi
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               value="password123"
                               required
                               placeholder="Ulangi kata sandi"
                               class="w-full px-4 py-2.5 bg-gray-50/70 border border-gray-200 rounded-xl text-sm text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#087a32]">
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-[#087a32] hover:bg-[#075523] text-white font-semibold py-2.5 px-4 rounded-xl text-sm shadow-xs transition cursor-pointer mt-2">
                    Daftar &amp; Masuk
                </button>
            </form>

            <p class="mt-5 text-center text-xs text-gray-500">
                Sudah punya akun Admin?
                <a href="{{ route('login') }}" class="font-bold text-[#087a32] hover:underline ml-1">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
</body>
</html>
