@extends('layouts.app')

@section('title', 'NordicRail — Travel, Refined.')

@section('content')
<div class="relative bg-[#FCFBF8] text-[#121212]">

    <!-- TOP HERO SLIDER (Inspired by kai.id with real Unsplash Photos: Service, Cabin, Journey, Station) -->
    <div x-data="{
        active: 0,
        total: 4,
        timer: null,
        slides: [
            {
                image: '{{ asset('images/slider/hero_scenic_train.jpg') }}',
                title: 'Pesona Lanskap Alam Nusantara di Setiap Rel',
                desc: 'Nikmati rute perjalanan antarkota berkecepatan tinggi dengan pemandangan pegunungan dan panorama alam yang memikat hati.',
                cta: 'Cari Jadwal Kereta',
                link: '#search-box'
            },
            {
                image: '{{ asset('images/slider/hero_service_hospitality.jpg') }}',
                title: 'Pelayanan Ramah & Berkelas Sepanjang Perjalanan',
                desc: 'Kru dan pramugara profesional kami siap memberikan pendampingan terbaik demi kenyamanan perjalanan Anda.',
                cta: 'Pelajari Layanan Kami',
                link: '{{ route('services') }}'
            },
            {
                image: '{{ asset('images/slider/hero_cabin_comfort.jpg') }}',
                title: 'Ketenangan & Privasi Ruang Duduk Ergonomis',
                desc: 'Kabin kedap suara dilengkapi kursi reclining luas, penyejuk udara optimal, dan fasilitas daya pribadi untuk istirahat Anda.',
                cta: 'Lihat Fasilitas Armada',
                link: '{{ route('services') }}'
            },
            {
                image: '{{ asset('images/slider/hero_station_sunset.jpg') }}',
                title: 'Konektivitas 16 Stasiun & Keberangkatan Teratur',
                desc: 'Sistem operasional rel terpadu menjamin waktu tempuh presisi, boarding praktis, dan ketibaan tepat waktu di stasiun tujuan.',
                cta: 'Cek Jadwal & Tarif',
                link: '{{ route('schedules') }}'
            }
        ],
        next() {
            this.active = (this.active + 1) % this.total;
        },
        goTo(i) {
            this.active = i;
        }
    }" 
    x-init="timer = setInterval(() => { active = (active + 1) % total }, 5000)"
    class="relative w-full overflow-hidden bg-[#121212] text-white min-h-[440px] sm:min-h-[500px] lg:min-h-[560px] flex items-end border-b border-[#2C2A26]">

        <!-- Slides Background Images with Smooth Crossfade -->
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="active === index"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-100"
                 class="absolute inset-0 w-full h-full">
                <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover object-center">
                <!-- Obsidian scrim overlay matching NordicRail theme -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#121212] via-black/65 to-black/35 sm:bg-gradient-to-r sm:from-black/90 sm:via-black/65 sm:to-black/30"></div>
            </div>
        </template>

        <!-- Slide Content Container -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-14 sm:pb-16">
            <div class="max-w-2xl space-y-4 sm:space-y-5">
                <!-- Title -->
                <h1 class="font-serif text-3xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white leading-[1.12] drop-shadow-sm transition-all duration-500"
                    x-text="slides[active].title"></h1>

                <!-- Description -->
                <p class="text-[#DAD6CD] text-sm sm:text-base lg:text-lg font-normal leading-relaxed max-w-xl transition-all duration-500"
                   x-text="slides[active].desc"></p>

                <!-- Actions & CTA -->
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a :href="slides[active].link"
                       class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-white text-[#121212] font-semibold text-sm hover:bg-[#F5F2EA] transition btn-press shadow-lg">
                        <span x-text="slides[active].cta"></span>
                        <span class="arrow-slide">&rarr;</span>
                    </a>
                    <a href="{{ route('promotions') }}"
                       class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl border border-white/25 bg-white/10 backdrop-blur-sm text-white font-semibold text-sm hover:bg-white/20 transition btn-press">
                        <span>Lihat Promosi</span>
                    </a>
                </div>
            </div>

            <!-- Bottom Navigation Bar & Progress Dash Indicators (KAI Style) -->
            <div class="mt-8 sm:mt-10 pt-5 border-t border-white/15 flex items-center justify-between gap-4">
                <!-- Dash Indicator Lines -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button type="button"
                                @click="goTo(index)"
                                class="h-1.5 rounded-full transition-all duration-300 focus:outline-none"
                                :class="active === index ? 'w-10 sm:w-16 bg-white' : 'w-5 sm:w-8 bg-white/30 hover:bg-white/60'"
                                :title="'Slide ' + (index + 1)"></button>
                    </template>
                </div>

                <!-- Slide Counter (Clean, No Arrows) -->
                <div class="flex items-center text-xs font-mono text-[#DAD6CD]">
                    <span class="font-bold text-white text-sm" x-text="'0' + (active + 1)"></span>
                    <span class="text-white/40 mx-1">/</span>
                    <span class="text-white/60">04</span>
                </div>
            </div>
        </div>
    </div>

    <!-- RESERVATION & SEARCH SECTION -->
    <section id="booking-section" data-section-index="01" data-section-title="Reservasi" class="relative pt-12 pb-20 border-b border-[#DAD6CD] bg-[#F5F2EA] overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Booking Widget Card -->
            <div id="search-box" class="bg-white rounded-3xl border border-[#DAD6CD] text-[#121212] p-6 sm:p-10 shadow-xs reveal-init card-60fps"
                 x-data="{ 
                    serviceTab: 'antarkota', 
                    tripType: 'one-way',
                    origin: '{{ $defaultOrigin?->id ?? 1 }}', 
                    destination: '{{ $defaultDestination?->id ?? 3 }}', 
                    passengers: 1, 
                    swap() { 
                        if (!this.origin && !this.destination) return;
                        let t = this.origin; 
                        this.origin = this.destination; 
                        this.destination = t; 
                    } 
                 }">

                <!-- Service Mode & Trip Mode Selection -->
                <div class="flex flex-wrap items-center justify-between border-b border-[#DAD6CD] pb-6 mb-8 gap-4">
                    <div class="flex items-center flex-wrap gap-3">
                        <div class="flex items-center gap-2 p-1 bg-[#F5F2EA] rounded-xl border border-[#DAD6CD]">
                            <button type="button" 
                                    @click="serviceTab = 'antarkota'" 
                                    class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                    :class="serviceTab === 'antarkota' ? 'bg-[#121212] text-white' : 'text-[#5E5C56] hover:text-[#121212]'">
                                Kereta Antarkota
                            </button>
                            <button type="button" 
                                    @click="serviceTab = 'panoramic'" 
                                    class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                    :class="serviceTab === 'panoramic' ? 'bg-[#121212] text-white' : 'text-[#5E5C56] hover:text-[#121212]'">
                                Panoramic & Wisata
                            </button>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-[#5E5C56]">(Tersedia di tanggal Genap Saja)</span>
                    </div>

                    <div class="flex items-center gap-6 text-sm font-medium text-[#5E5C56]">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" x-model="tripType" value="one-way" class="text-[#121212] focus:ring-[#121212] h-4 w-4">
                            <span :class="tripType === 'one-way' ? 'text-[#121212] font-semibold' : 'text-[#5E5C56]'">Sekali Jalan</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" x-model="tripType" value="round-trip" class="text-[#121212] focus:ring-[#121212] h-4 w-4">
                            <span :class="tripType === 'round-trip' ? 'text-[#121212] font-semibold' : 'text-[#5E5C56]'">Pulang-Pergi</span>
                        </label>
                    </div>
                </div>

                <!-- Form Controls -->
                <form action="{{ route('search') }}" method="GET">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-end">
                        
                        <!-- Origin & Destination -->
                        <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-11 gap-3 items-center">
                            <div class="sm:col-span-5">
                                <label class="block text-sm font-medium text-[#121212] mb-1.5">
                                    Stasiun Asal
                                </label>
                                <select name="origin" x-model="origin" required 
                                        class="w-full bg-[#FCFBF8] border border-[#DAD6CD] rounded-xl px-4 py-3 text-sm text-[#121212] focus:bg-white focus:ring-1 focus:ring-[#121212] transition">
                                    <option value="" disabled>Pilih Keberangkatan</option>
                                    @foreach($stations as $station)
                                        <option value="{{ $station->id }}" {{ (($defaultOrigin?->id ?? 1) == $station->id) ? 'selected' : '' }}>
                                            {{ $station->name }} ({{ $station->code }}) - {{ $station->city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Swap Action -->
                            <div class="sm:col-span-1 flex justify-center pt-2 sm:pt-6">
                                <button type="button" 
                                        @click="swap()" 
                                        title="Tukar stasiun asal dan tujuan"
                                        class="w-10 h-10 rounded-full border border-[#DAD6CD] bg-[#F5F2EA] text-sm font-semibold text-[#121212] hover:bg-white transition flex items-center justify-center btn-swap shadow-2xs">
                                    &harr;
                                </button>
                            </div>

                            <div class="sm:col-span-5">
                                <label class="block text-sm font-medium text-[#121212] mb-1.5">
                                    Stasiun Tujuan
                                </label>
                                <select name="destination" x-model="destination" required 
                                        class="w-full bg-[#FCFBF8] border border-[#DAD6CD] rounded-xl px-4 py-3 text-sm text-[#121212] focus:bg-white focus:ring-1 focus:ring-[#121212] transition">
                                    <option value="" disabled>Pilih Tujuan</option>
                                    @foreach($stations as $station)
                                        <option value="{{ $station->id }}" {{ (($defaultDestination?->id ?? 3) == $station->id) ? 'selected' : '' }}>
                                            {{ $station->name }} ({{ $station->code }}) - {{ $station->city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-[#121212] mb-1.5">
                                Keberangkatan
                            </label>
                            <input type="date" 
                                   name="date" 
                                   id="travel-date-input"
                                   min="{{ date('Y-m-d') }}" 
                                   value="{{ date('Y-m-d') }}" 
                                   required 
                                   class="w-full bg-[#FCFBF8] border border-[#DAD6CD] rounded-xl px-4 py-3 text-sm text-[#121212] focus:bg-white focus:ring-1 focus:ring-[#121212] transition">
                        </div>

                        <!-- Passengers -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-[#121212] mb-1.5">
                                Penumpang
                            </label>
                            <select name="passengers" x-model="passengers" 
                                    class="w-full bg-[#FCFBF8] border border-[#DAD6CD] rounded-xl px-4 py-3 text-sm text-[#121212] focus:bg-white focus:ring-1 focus:ring-[#121212] transition">
                                <option value="1">1 Dewasa</option>
                                <option value="2">2 Dewasa</option>
                                <option value="3">3 Dewasa</option>
                                <option value="4">4 Dewasa</option>
                                <option value="5">5 Dewasa</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="lg:col-span-2">
                            <button type="submit" 
                                    class="w-full bg-[#121212] text-white py-3.5 px-6 rounded-xl font-medium text-sm hover:bg-black transition shadow-xs text-center btn-press">
                                Cari Jadwal &rarr;
                            </button>
                        </div>

                    </div>
                </form>

            </div>

            <!-- Key Metric Counters (Scroll Animated 0 -> Target Main Number) -->
            <div class="mt-14 grid grid-cols-2 md:grid-cols-4 gap-6 pt-8 border-t border-[#DAD6CD]/70 text-center reveal-init">
                <div class="space-y-1">
                    <div class="font-serif text-3xl sm:text-4xl font-bold text-[#121212] tracking-tight">
                        <span data-counter-target="16" data-counter-duration="1300">0</span>
                    </div>
                    <p class="text-xs sm:text-sm text-[#5E5C56]">Stasiun Terhubung</p>
                </div>
                <div class="space-y-1">
                    <div class="font-serif text-3xl sm:text-4xl font-bold text-[#121212] tracking-tight">
                        <span data-counter-target="52" data-counter-duration="1500">0</span>
                    </div>
                    <p class="text-xs sm:text-sm text-[#5E5C56]">Rute Antarkota Aktif</p>
                </div>
                <div class="space-y-1">
                    <div class="font-serif text-3xl sm:text-4xl font-bold text-[#121212] tracking-tight">
                        <span data-counter-target="14" data-counter-duration="1200">0</span>
                    </div>
                    <p class="text-xs sm:text-sm text-[#5E5C56]">Rangkaian Kereta</p>
                </div>
                <div class="space-y-1">
                    <div class="font-serif text-3xl sm:text-4xl font-bold text-[#121212] tracking-tight">
                        <span data-counter-target="100" data-counter-suffix="%" data-counter-duration="1400">0%</span>
                    </div>
                    <p class="text-xs sm:text-sm text-[#5E5C56]">Tarif Real-Time Transparan</p>
                </div>
            </div>

        </div>
    </section>

    <!-- POPULAR ROUTES SECTION -->
    <section id="routes-section" data-section-index="02" data-section-title="Rute Pilihan" class="py-20 border-b border-[#DAD6CD] bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4 reveal-init">
                <div>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-[#121212] tracking-tight">
                        Rute & Destinasi Pilihan
                    </h2>
                    <p class="text-sm text-[#5E5C56] mt-2">Konektivitas antarkota dengan pilihan jadwal harian terlengkap.</p>
                </div>
                <a href="{{ route('schedules') }}" class="text-sm font-semibold text-[#121212] hover:underline inline-flex items-center gap-1 group">
                    <span>Lihat Semua Jadwal</span>
                    <span class="transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
                </a>
            </div>

            <!-- Route Grid Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($popularRoutes as $index => $route)
                    <div class="p-6 rounded-2xl border border-[#DAD6CD] bg-[#FCFBF8] card-60fps group flex flex-col justify-between reveal-init delay-{{ ($index % 3 + 1) * 100 }}">
                        <div>
                            <div class="font-serif text-xl font-bold text-[#121212] mb-1 group-hover:text-black transition-colors">
                                {{ $route['from'] }} <span class="arrow-slide">&rarr;</span> {{ $route['to'] }}
                            </div>
                            <p class="text-sm text-[#5E5C56]">Waktu tempuh sekitar {{ $route['duration'] }}</p>
                        </div>

                        <div class="mt-8 pt-4 border-t border-[#DAD6CD] flex items-center justify-between">
                            <div>
                                <span class="text-xs text-[#5E5C56] block">Mulai dari</span>
                                <span class="text-base font-bold text-[#121212]">Rp {{ number_format($route['price'], 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('search', ['origin' => $route['origin_id'], 'destination' => $route['dest_id'], 'date' => date('Y-m-d'), 'passengers' => 1]) }}" 
                               class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#121212] group-hover:underline">
                                <span>Cek Kursi</span>
                                <span class="arrow-slide">&rarr;</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- FLEET & EXPERIENCE SECTION -->
    <section id="fleet-section" data-section-index="03" data-section-title="Armada Kereta" class="py-20 border-b border-[#DAD6CD] bg-[#F5F2EA]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="max-w-2xl mb-12 reveal-init">
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-[#121212] tracking-tight mb-3">
                    Armada Kereta Api
                </h2>
                <p class="text-sm text-[#5E5C56] leading-relaxed">
                    Rangkaian kereta dirancang untuk menghadirkan ketenangan dan kenyamanan selama perjalanan, dengan kabin kedap suara dan tata letak kursi ergonomis.
                </p>
            </div>

            <!-- Fleet Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredTrains as $index => $train)
                    <div class="bg-white rounded-2xl border border-[#DAD6CD] p-6 flex flex-col justify-between card-60fps reveal-init delay-{{ ($index + 1) * 100 }}">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-semibold px-2 py-1 bg-[#F5F2EA] rounded text-[#121212] border border-[#DAD6CD]">
                                    {{ $train->number }}
                                </span>
                                <span class="text-xs text-[#5E5C56]">Operasional Harian</span>
                            </div>
                            <h3 class="font-serif text-xl font-bold text-[#121212] mb-2">
                                {{ $train->name }}
                            </h3>
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @foreach($train->classes as $c)
                                    <span class="text-xs font-medium text-[#5E5C56]">
                                        {{ ucfirst($c->class_type) }}{{ !$loop->last ? ' •' : '' }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-4 border-t border-[#DAD6CD] text-xs text-[#5E5C56] space-y-1">
                            <p>Fasilitas: WiFi, Stopkontak, AC, Bagasi</p>
                            <p class="text-[#121212] font-medium pt-1">Konfigurasi Kursi Nyaman</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 text-center reveal-init">
                <a href="{{ route('services') }}" class="group inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold border border-[#DAD6CD] bg-white text-[#121212] hover:bg-[#FAF8F5] transition btn-press shadow-2xs">
                    <span>Pelajari Selengkapnya Tentang Armada Kami</span>
                    <span class="arrow-slide">&rarr;</span>
                </a>
            </div>

            <!-- Cabin Comfort Showcase -->
            <div class="mt-16 bg-white rounded-3xl border border-[#DAD6CD] overflow-hidden p-3 sm:p-4 card-60fps reveal-init">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-7 rounded-2xl overflow-hidden aspect-[16/9] bg-[#EFECE3] img-zoom-box group">
                        <img src="{{ asset('images/train_interior.jpg') }}" 
                             alt="NordicRail Luxury First Class Cabin" 
                             class="w-full h-full object-cover img-zoom group-hover:scale-105">
                    </div>
                    <div class="lg:col-span-5 p-4 sm:p-6 space-y-4">
                        <span class="text-xs px-2.5 py-1 rounded-full bg-[#F5F2EA] border border-[#DAD6CD] text-[#5E5C56] font-medium">
                            Kenyamanan Kabin Eksekutif
                        </span>
                        <h3 class="font-serif text-2xl sm:text-3xl font-bold text-[#121212] leading-snug">
                            Ketenangan & Privasi di Sepanjang Jalur Rel
                        </h3>
                        <p class="text-sm text-[#5E5C56] leading-relaxed">
                            Nikmati ruang duduk luas berlapis kulit lembut, pencahayaan ambien hangat, serta jendela panorama luas untuk menyaksikan pesona alam pegunungan Nusantara secara langsung.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- SERVICE PILLARS SECTION -->
    <section id="pillars-section" data-section-index="04" data-section-title="Layanan Kami" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h2 class="font-serif text-3xl sm:text-4xl font-bold text-[#121212] tracking-tight mb-12 reveal-init">
                Layanan NordicRail
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-3 reveal-init delay-100 p-8 rounded-2xl border border-[#DAD6CD] bg-[#FCFBF8] card-60fps">
                    <h3 class="font-serif text-xl font-bold text-[#121212]">Harga Transparan & Terbuka</h3>
                    <p class="text-sm text-[#5E5C56] leading-relaxed">
                        Harga tiket yang tertera adalah tarif sebenarnya yang dibayarkan. Rincian biaya layanan dan diskon promo ditampilkan jelas tanpa biaya tersembunyi.
                    </p>
                </div>

                <div class="space-y-3 reveal-init delay-200 p-8 rounded-2xl border border-[#DAD6CD] bg-[#FCFBF8] card-60fps">
                    <h3 class="font-serif text-xl font-bold text-[#121212]">Pemilihan Kursi Langsung</h3>
                    <p class="text-sm text-[#5E5C56] leading-relaxed">
                        Tentukan posisi duduk Anda langsung dari peta gerbong interaktif secara real-time untuk memastikan perjalanan nyaman bersama pendamping.
                    </p>
                </div>

                <div class="space-y-3 reveal-init delay-300 p-8 rounded-2xl border border-[#DAD6CD] bg-[#FCFBF8] card-60fps">
                    <h3 class="font-serif text-xl font-bold text-[#121212]">E-Tiket & Boarding Praktis</h3>
                    <p class="text-sm text-[#5E5C56] leading-relaxed">
                        Setelah pembayaran terkonfirmasi, e-tiket resmi langsung diterbitkan dan siap digunakan saat pemeriksaan di pintu masuk stasiun.
                    </p>
                </div>
            </div>

        </div>
    </section>

</div>
@endsection
