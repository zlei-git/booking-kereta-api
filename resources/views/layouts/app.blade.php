<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NordicRail — High-End Railway Network')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & DM Sans (Matching webcareidn.com) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300..800;1,9..40,300..800&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-[#FAF8F5] text-slate-800 antialiased flex flex-col min-h-screen selection:bg-[#121212] selection:text-white">

    <!-- Navigation Header (No Icons, Pure Typography & Spacious Layout) -->
    <header class="bg-white border-b border-[#DAD6CD] sticky top-0 z-50 transition-all" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Pure Typographic Logo (No Icon Box) -->
                <div class="flex items-center gap-10">
                    <a href="{{ route('home') }}" class="inline-block">
                        <span class="font-serif text-2xl sm:text-3xl font-bold tracking-tight text-[#121212] leading-none">NordicRail</span>
                    </a>

                    <!-- Desktop Nav Links (No Icons) -->
                    <nav class="hidden lg:flex items-center space-x-1">
                        <a href="{{ route('home') }}" 
                           class="px-3.5 py-2 text-sm font-medium rounded-md transition-colors nav-link-animated {{ request()->routeIs('home') ? 'text-[#121212] font-bold nav-active' : 'text-[#5E5C56] hover:text-[#121212] hover:bg-[#F5F2EA]' }}">
                           Beranda
                        </a>
                        <a href="{{ route('schedules') }}" 
                           class="px-3.5 py-2 text-sm font-medium rounded-md transition-colors nav-link-animated {{ request()->routeIs('schedules*') ? 'text-[#121212] font-bold nav-active' : 'text-[#5E5C56] hover:text-[#121212] hover:bg-[#F5F2EA]' }}">
                           Jadwal & Tarif
                        </a>
                        <a href="{{ route('services') }}" 
                           class="px-3.5 py-2 text-sm font-medium rounded-md transition-colors nav-link-animated {{ request()->routeIs('services') ? 'text-[#121212] font-bold nav-active' : 'text-[#5E5C56] hover:text-[#121212] hover:bg-[#F5F2EA]' }}">
                           Armada & Layanan
                        </a>
                        <a href="{{ route('promotions') }}" 
                           class="px-3.5 py-2 text-sm font-medium rounded-md transition-colors nav-link-animated {{ request()->routeIs('promotions*') ? 'text-[#121212] font-bold nav-active' : 'text-[#5E5C56] hover:text-[#121212] hover:bg-[#F5F2EA]' }}">
                           Promosi
                        </a>
                        <a href="{{ route('guide') }}" 
                           class="px-3.5 py-2 text-sm font-medium rounded-md transition-colors nav-link-animated {{ request()->routeIs('guide') ? 'text-[#121212] font-bold nav-active' : 'text-[#5E5C56] hover:text-[#121212] hover:bg-[#F5F2EA]' }}">
                           Panduan
                        </a>
                        <a href="{{ route('contact') }}" 
                           class="px-3.5 py-2 text-sm font-medium rounded-md transition-colors nav-link-animated {{ request()->routeIs('contact') ? 'text-[#121212] font-bold nav-active' : 'text-[#5E5C56] hover:text-[#121212] hover:bg-[#F5F2EA]' }}">
                           Hubungi Kami
                        </a>
                    </nav>
                </div>

                <!-- Right Utility / User Auth -->
                <div class="hidden sm:flex items-center gap-4">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-md text-xs font-semibold bg-navy-100 text-navy-900 hover:bg-navy-200 transition btn-press">
                                Admin Panel
                            </a>
                        @endif

                        <a href="{{ route('orders.index') }}" 
                           class="px-3.5 py-2 text-sm font-medium text-slate-700 hover:text-navy-900 hover:bg-slate-100 rounded-md transition btn-press">
                            Pesanan Saya
                        </a>

                        <div class="relative" x-data="{ userMenu: false }">
                            <button @click="userMenu = !userMenu" class="flex items-center gap-2 pl-3 pr-2 py-1.5 rounded-full border border-slate-200 hover:border-slate-300 text-sm font-medium text-slate-700 focus:outline-none transition btn-press">
                                <span class="w-7 h-7 rounded-full bg-navy-900 text-white flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <span class="max-w-[120px] truncate text-slate-800">{{ auth()->user()->name }}</span>
                            </button>

                            <div x-show="userMenu" @click.away="userMenu = false" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-52 rounded-xl shadow-xl py-1.5 bg-white border border-slate-100 ring-1 ring-black/5 focus:outline-none z-50" style="display: none;">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-xs text-slate-400">Masuk sebagai</p>
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">Tiket & Pesanan</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">Keluar (Logout)</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-navy-900 transition btn-press">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md text-sm font-semibold text-white bg-accent-600 hover:bg-accent-700 shadow-sm transition btn-press">
                            Daftar Akun
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="flex items-center lg:hidden">
                    <button @click="mobileMenu = !mobileMenu" type="button" class="px-3 py-1.5 rounded-md border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none">
                        <span x-show="!mobileMenu">Menu</span>
                        <span x-show="mobileMenu" style="display: none;">Tutup</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer (Clean Typography, No Icons) -->
        <div x-show="mobileMenu" x-transition class="lg:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-3" style="display: none;">
            <div class="flex flex-col space-y-1">
                <a href="{{ route('home') }}" class="px-3 py-2 text-base font-medium rounded-md text-[#121212] hover:bg-[#F5F2EA]">Beranda</a>
                <a href="{{ route('schedules') }}" class="px-3 py-2 text-base font-medium rounded-md text-[#121212] hover:bg-[#F5F2EA]">Jadwal & Tarif</a>
                <a href="{{ route('services') }}" class="px-3 py-2 text-base font-medium rounded-md text-[#121212] hover:bg-[#F5F2EA]">Armada & Layanan</a>
                <a href="{{ route('promotions') }}" class="px-3 py-2 text-base font-medium rounded-md text-[#121212] hover:bg-[#F5F2EA]">Promosi</a>
                <a href="{{ route('guide') }}" class="px-3 py-2 text-base font-medium rounded-md text-[#121212] hover:bg-[#F5F2EA]">Panduan Perjalanan</a>
                <a href="{{ route('contact') }}" class="px-3 py-2 text-base font-medium rounded-md text-[#121212] hover:bg-[#F5F2EA]">Hubungi Kami</a>
            </div>

            <div class="pt-4 border-t border-slate-200">
                @guest
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('login') }}" class="block text-center py-2.5 px-4 border border-slate-300 rounded-md text-sm font-semibold text-slate-700 hover:bg-slate-50">Masuk</a>
                        <a href="{{ route('register') }}" class="block text-center py-2.5 px-4 rounded-md text-sm font-semibold text-white bg-[#121212] hover:bg-black">Daftar</a>
                    </div>
                @else
                    <div class="flex items-center gap-3 px-3 py-2 mb-2 bg-slate-50 rounded-lg">
                        <span class="w-8 h-8 rounded-full bg-[#121212] text-white flex items-center justify-center text-sm font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <div>
                            <div class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-slate-500">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <a href="{{ route('orders.index') }}" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-md">Pesanan Saya</a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-sm font-medium text-navy-900 bg-navy-50 rounded-md">Admin Dashboard</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-md">Keluar</button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </header>

    <!-- Notification / Flash Alerts -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full" x-data="{ show: true }" x-show="show" x-transition>
            <div class="bg-emerald-50 border-l-4 border-emerald-600 p-4 rounded-r-lg shadow-sm flex justify-between items-center">
                <p class="text-sm font-medium text-emerald-900">{{ session('success') }}</p>
                <button @click="show = false" class="text-emerald-700 hover:text-emerald-900 text-sm font-semibold">Tutup</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full" x-data="{ show: true }" x-show="show" x-transition>
            <div class="bg-rose-50 border-l-4 border-[#7E171E] p-4 rounded-r-lg shadow-sm flex justify-between items-center">
                <p class="text-sm font-medium text-rose-900">{{ session('error') }}</p>
                <button @click="show = false" class="text-rose-700 hover:text-rose-900 text-sm font-semibold">Tutup</button>
            </div>
        </div>
    @endif

    <!-- Content Slot -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Refined Footer (Editorial Typography, No Icons) -->
    <footer class="bg-[#121212] text-[#DAD6CD] border-t border-[#2C2A26] mt-20">
        <div class="max-w-7xl mx-auto pt-16 pb-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
                
                <div class="lg:col-span-2 space-y-4 reveal-init">
                    <span class="font-serif text-2xl font-bold text-white tracking-tight">NordicRail</span>
                    <p class="text-sm text-[#8B887F] leading-relaxed max-w-sm">
                        Layanan transportasi perkeretaapian modern di Indonesia dengan standar kenyamanan tinggi, transparansi tarif real-time, dan ketepatan waktu perjalanan.
                    </p>
                    <div class="pt-2 text-xs text-[#8B887F] space-y-1">
                        <p>Stasiun Gambir Lt. 3, Jakarta Pusat</p>
                        <p>layanan@nordicrail.id</p>
                    </div>
                </div>

                <div class="reveal-init delay-100">
                    <h3 class="font-serif text-base font-semibold text-white tracking-wide mb-4">Layanan</h3>
                    <ul class="space-y-2.5 text-sm text-[#8B887F]">
                        <li><a href="{{ route('schedules') }}" class="hover:text-white transition-colors">Jadwal & Tarif</a></li>
                        <li><a href="{{ route('services') }}" class="hover:text-white transition-colors">Armada Kereta Api</a></li>
                        <li><a href="{{ route('promotions') }}" class="hover:text-white transition-colors">Program Promo</a></li>
                        <li><a href="{{ route('services') }}" class="hover:text-white transition-colors">Eksekutif New Generation</a></li>
                    </ul>
                </div>

                <div class="reveal-init delay-200">
                    <h3 class="font-serif text-base font-semibold text-white tracking-wide mb-4">Informasi</h3>
                    <ul class="space-y-2.5 text-sm text-[#8B887F]">
                        <li><a href="{{ route('guide') }}" class="hover:text-white transition-colors">Syarat Identitas Boarding</a></li>
                        <li><a href="{{ route('guide') }}" class="hover:text-white transition-colors">Ketentuan Bagasi</a></li>
                        <li><a href="{{ route('guide') }}" class="hover:text-white transition-colors">Pembatalan & Ubah Jadwal</a></li>
                        <li><a href="{{ route('guide') }}" class="hover:text-white transition-colors">Hak Reduksi & Diskon</a></li>
                    </ul>
                </div>

                <div class="reveal-init delay-300">
                    <h3 class="font-serif text-base font-semibold text-white tracking-wide mb-4">Bantuan</h3>
                    <div class="space-y-3 text-sm text-[#8B887F]">
                        <div class="p-3 rounded-lg bg-[#1C1B19] border border-[#2C2A26]">
                            <span class="text-xs text-[#8B887F] block">Contact Center:</span>
                            <span class="text-lg font-bold text-[#B7A07A]">121 / (021) 121</span>
                        </div>
                        <div class="pt-1">
                            <a href="{{ route('contact') }}" class="inline-block text-xs font-semibold text-[#B7A07A] hover:text-[#D6C2A5]">
                                Kirim Pesan & Masukan &rarr;
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-14 pt-8 border-t border-[#2C2A26] flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-[#8B887F]">
                <p>&copy; 2026 PT NordicRail Indonesia. Hak cipta dilindungi.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('admin.login') }}" class="text-[#8B887F] hover:text-white transition">Portal Staf</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
