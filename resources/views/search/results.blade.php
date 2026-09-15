@extends('layouts.app')

@section('title', 'Jadwal & Hasil Pencarian Kereta — NordicRail')

@section('content')
<div x-data="{ showSearch: {{ $searched ? 'false' : 'true' }} }" class="bg-[#FCFBF8] min-h-screen pb-16">

    <!-- Search Query Summary Bar -->
    @if($searched)
    <div class="bg-white border-b border-[#DAD6CD] py-4 px-4 sm:px-6 shadow-xs sticky top-20 z-20">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex flex-wrap items-center gap-3 text-sm text-[#121212]">
                <span class="font-serif font-bold text-base sm:text-lg">{{ $origin?->name ?? 'Semua Stasiun' }}</span>
                <span class="text-[#5E5C56]">&rarr;</span>
                <span class="font-serif font-bold text-base sm:text-lg">{{ $destination?->name ?? 'Semua Stasiun' }}</span>
                <span class="text-[#DAD6CD]">|</span>
                <span class="text-[#5E5C56] font-medium">{{ $date ? \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') : 'Hari Ini' }}</span>
                <span class="text-[#DAD6CD]">|</span>
                <span class="text-[#5E5C56] font-medium">{{ $passengers }} Penumpang</span>
            </div>
            <button type="button" 
                    @click="showSearch = !showSearch" 
                    class="text-xs font-semibold px-4 py-2 rounded-lg border border-[#DAD6CD] bg-[#F5F2EA] text-[#121212] hover:bg-white transition">
                <span x-show="!showSearch">Ubah Pencarian</span>
                <span x-show="showSearch" style="display: none;">Tutup Pencarian</span>
            </button>
        </div>
    </div>
    @endif

    <!-- Collapsible Search Modification Form -->
    <div x-show="showSearch" x-transition class="bg-white border-b border-[#DAD6CD] py-6 px-4 sm:px-6 shadow-xs">
        <div class="max-w-6xl mx-auto">
            <form action="{{ route('search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-[#121212] mb-1.5">Stasiun Asal</label>
                    <select name="origin" required class="w-full rounded-xl border-[#DAD6CD] bg-[#FCFBF8] text-sm text-[#121212] py-2.5 px-3 focus:ring-1 focus:ring-[#121212]">
                        @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ ($origin?->id == $station->id) ? 'selected' : '' }}>
                                {{ $station->name }} ({{ $station->code }}) - {{ $station->city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-[#121212] mb-1.5">Stasiun Tujuan</label>
                    <select name="destination" required class="w-full rounded-xl border-[#DAD6CD] bg-[#FCFBF8] text-sm text-[#121212] py-2.5 px-3 focus:ring-1 focus:ring-[#121212]">
                        @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ ($destination?->id == $station->id) ? 'selected' : '' }}>
                                {{ $station->name }} ({{ $station->code }}) - {{ $station->city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#121212] mb-1.5">Tanggal</label>
                    <input type="date" name="date" required value="{{ $date }}" min="{{ date('Y-m-d') }}" class="w-full rounded-xl border-[#DAD6CD] bg-[#FCFBF8] text-sm text-[#121212] py-2.5 px-3 focus:ring-1 focus:ring-[#121212]">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#121212] mb-1.5">Penumpang</label>
                    <select name="passengers" class="w-full rounded-xl border-[#DAD6CD] bg-[#FCFBF8] text-sm text-[#121212] py-2.5 px-3 focus:ring-1 focus:ring-[#121212]">
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ $passengers == $i ? 'selected' : '' }}>{{ $i }} Dewasa</option>
                        @endfor
                    </select>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="w-full bg-[#121212] text-white py-2.5 px-4 rounded-xl text-sm font-medium hover:bg-black transition">
                        Perbarui Hasil
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Results Section -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">

        @if(!$searched)
            <div class="mb-6 p-5 rounded-2xl bg-[#F5F2EA] border border-[#DAD6CD] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-sm">
                <div>
                    <h3 class="font-bold text-[#121212]">Jadwal Keberangkatan Populer</h3>
                    <p class="text-xs text-[#5E5C56] mt-0.5">Pilih stasiun asal dan stasiun tujuan pada formulir di atas untuk mencari jadwal kereta rute spesifik Anda.</p>
                </div>
            </div>
        @endif

        <!-- Filter Bar & Sorting Row -->
        <div class="bg-white border border-[#DAD6CD] rounded-2xl p-4 sm:p-5 mb-8 shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            
            <!-- Quick Filters -->
            <div class="flex flex-wrap items-center gap-4 text-xs">
                <span class="font-medium text-sm text-[#121212]">Filter:</span>

                <!-- Availability Filter -->
                <div class="flex items-center gap-1 bg-[#F5F2EA] p-1 rounded-lg border border-[#DAD6CD]">
                    <a href="{{ request()->fullUrlWithQuery(['availability' => 'all']) }}" 
                       class="px-2.5 py-1 rounded font-medium transition {{ ($availability ?? 'all') === 'all' ? 'bg-[#121212] text-white' : 'text-[#5E5C56] hover:text-[#121212]' }}">
                        Semua Jadwal
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['availability' => 'available_only']) }}" 
                       class="px-2.5 py-1 rounded font-medium transition {{ ($availability ?? '') === 'available_only' ? 'bg-[#121212] text-white' : 'text-[#5E5C56] hover:text-[#121212]' }}">
                        Tersedia Saja
                    </a>
                </div>

                <!-- Time Slot Filter -->
                <div class="flex items-center gap-1 bg-[#F5F2EA] p-1 rounded-lg border border-[#DAD6CD]">
                    <a href="{{ request()->fullUrlWithQuery(['time_slot' => null]) }}" 
                       class="px-2 py-1 rounded font-medium transition {{ empty($timeSlot) ? 'bg-[#121212] text-white' : 'text-[#5E5C56] hover:text-[#121212]' }}">
                        Semua Waktu
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['time_slot' => 'pagi']) }}" 
                       class="px-2 py-1 rounded font-medium transition {{ ($timeSlot ?? '') === 'pagi' ? 'bg-[#121212] text-white' : 'text-[#5E5C56] hover:text-[#121212]' }}">
                        Pagi (00-12)
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['time_slot' => 'siang']) }}" 
                       class="px-2 py-1 rounded font-medium transition {{ ($timeSlot ?? '') === 'siang' ? 'bg-[#121212] text-white' : 'text-[#5E5C56] hover:text-[#121212]' }}">
                        Siang (12-16)
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['time_slot' => 'sore']) }}" 
                       class="px-2 py-1 rounded font-medium transition {{ ($timeSlot ?? '') === 'sore' ? 'bg-[#121212] text-white' : 'text-[#5E5C56] hover:text-[#121212]' }}">
                        Sore (16-19)
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['time_slot' => 'malam']) }}" 
                       class="px-2 py-1 rounded font-medium transition {{ ($timeSlot ?? '') === 'malam' ? 'bg-[#121212] text-white' : 'text-[#5E5C56] hover:text-[#121212]' }}">
                        Malam (19-24)
                    </a>
                </div>

                <!-- Class Filter -->
                <div class="flex items-center gap-1 bg-[#F5F2EA] p-1 rounded-lg border border-[#DAD6CD]">
                    <a href="{{ request()->fullUrlWithQuery(['class' => null]) }}" 
                       class="px-2 py-1 rounded font-medium transition {{ empty($classFilter) ? 'bg-[#121212] text-white' : 'text-[#5E5C56] hover:text-[#121212]' }}">
                        Semua Kelas
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['class' => 'eksekutif']) }}" 
                       class="px-2 py-1 rounded font-medium transition {{ ($classFilter ?? '') === 'eksekutif' ? 'bg-[#121212] text-white' : 'text-[#5E5C56] hover:text-[#121212]' }}">
                        Eksekutif
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['class' => 'bisnis']) }}" 
                       class="px-2 py-1 rounded font-medium transition {{ ($classFilter ?? '') === 'bisnis' ? 'bg-[#121212] text-white' : 'text-[#5E5C56] hover:text-[#121212]' }}">
                        Bisnis
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['class' => 'ekonomi']) }}" 
                       class="px-2 py-1 rounded font-medium transition {{ ($classFilter ?? '') === 'ekonomi' ? 'bg-[#121212] text-white' : 'text-[#5E5C56] hover:text-[#121212]' }}">
                        Ekonomi
                    </a>
                </div>
            </div>

            <!-- Sorting -->
            <div class="flex items-center gap-2 text-xs border-t lg:border-t-0 pt-3 lg:pt-0 border-[#DAD6CD]">
                <span class="text-sm font-medium text-[#121212]">Urutkan:</span>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price']) }}" 
                   class="px-3 py-1.5 rounded-lg border border-[#DAD6CD] font-medium transition {{ ($sort ?? 'price') === 'price' ? 'bg-[#121212] text-white' : 'bg-[#FCFBF8] text-[#5E5C56] hover:text-[#121212]' }}">
                    Tarif Terendah
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'departure']) }}" 
                   class="px-3 py-1.5 rounded-lg border border-[#DAD6CD] font-medium transition {{ ($sort ?? '') === 'departure' ? 'bg-[#121212] text-white' : 'bg-[#FCFBF8] text-[#5E5C56] hover:text-[#121212]' }}">
                    Berangkat Awal
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'duration']) }}" 
                   class="px-3 py-1.5 rounded-lg border border-[#DAD6CD] font-medium transition {{ ($sort ?? '') === 'duration' ? 'bg-[#121212] text-white' : 'bg-[#FCFBF8] text-[#5E5C56] hover:text-[#121212]' }}">
                    Durasi Tercepat
                </a>
            </div>

        </div>

        <!-- Schedule Count & Header -->
        <div class="flex items-center justify-between mb-6">
            <span class="text-sm font-medium text-[#121212]">
                Ditemukan <strong>{{ $schedules->count() }}</strong> jadwal kereta
            </span>
            <div class="flex items-center gap-4 text-xs text-[#5E5C56]">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    Tersedia
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#8B887F]"></span>
                    Tiket Habis
                </span>
            </div>
        </div>

        <!-- Schedules List (Available & Unavailable) -->
        @if($schedules->isEmpty())
            <div class="bg-white border border-[#DAD6CD] rounded-2xl p-8 sm:p-12 text-center shadow-xs max-w-2xl mx-auto">
                @if(!empty($isOddDate))
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-[#F5F2EA] text-[#121212] font-semibold text-base mb-4 border border-[#DAD6CD]">
                        {{ \Carbon\Carbon::parse($date)->format('d') }}
                    </div>
                    <h3 class="font-serif text-2xl font-bold text-[#121212] mb-2">Kereta Beroperasi di Tanggal Genap</h3>
                    <p class="text-sm text-[#5E5C56] max-w-md mx-auto mb-6">
                        Jadwal keberangkatan untuk rute ini tersedia pada <strong>tanggal genap (2, 4, 6, 8, ...)</strong>. Tanggal yang Anda pilih ({{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}) merupakan tanggal ganjil.
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        @if(!empty($nextEvenDate))
                            <a href="{{ request()->fullUrlWithQuery(['date' => $nextEvenDate]) }}" 
                               class="px-5 py-2.5 rounded-xl text-xs font-semibold bg-[#121212] text-white hover:bg-black transition shadow-xs">
                                Lihat Tanggal {{ \Carbon\Carbon::parse($nextEvenDate)->translatedFormat('d M Y') }} &rarr;
                            </a>
                        @endif
                        @if(!empty($prevEvenDate))
                            <a href="{{ request()->fullUrlWithQuery(['date' => $prevEvenDate]) }}" 
                               class="px-5 py-2.5 rounded-xl text-xs font-semibold bg-[#F5F2EA] text-[#121212] border border-[#DAD6CD] hover:bg-white transition">
                                &larr; Atau Tanggal {{ \Carbon\Carbon::parse($prevEvenDate)->translatedFormat('d M Y') }}
                            </a>
                        @endif
                    </div>
                @else
                    <h3 class="font-serif text-2xl font-bold text-[#121212] mb-2">Tidak Ada Jadwal Ditemukan</h3>
                    <p class="text-sm text-[#5E5C56] max-w-md mx-auto mb-6">
                        Tidak ada jadwal kereta yang sesuai dengan kriteria pencarian Anda. Silakan coba pilih stasiun lain atau ubah filter pencarian.
                    </p>
                    <button @click="showSearch = true" class="px-5 py-2.5 rounded-xl text-xs font-semibold bg-[#121212] text-white hover:bg-black transition">
                        Ubah Pencarian
                    </button>
                @endif
            </div>
        @else
            <div class="space-y-4">
                @foreach($schedules as $schedule)
                    @php
                        $seatsLeft = $schedule->getAvailableSeatsCount();
                        $isAvailable = $schedule->is_active && ($seatsLeft >= $passengers);
                    @endphp

                    <div class="border rounded-2xl p-6 transition shadow-xs card-60fps reveal-init delay-{{ ($loop->index % 4 + 1) * 75 }} {{ $isAvailable ? 'bg-white border-[#DAD6CD] hover:border-[#121212]' : 'bg-[#F9F8F6] border-[#E5E2DA] opacity-80' }}">
                        
                        <!-- Top Row: Train Identity & Status Badge -->
                        <div class="flex flex-wrap items-center justify-between pb-4 mb-4 border-b border-[#EFECE3] gap-3">
                            <div class="flex items-center gap-3">
                                <h3 class="font-serif text-xl font-bold text-[#121212]">
                                    {{ $schedule->train->name ?? 'Kereta Api' }}
                                </h3>
                                <span class="text-xs px-2 py-0.5 bg-[#F5F2EA] rounded border border-[#DAD6CD] text-[#5E5C56]">
                                    {{ $schedule->train->number ?? '-' }}
                                </span>
                                <span class="text-xs font-medium px-2.5 py-0.5 rounded border border-[#DAD6CD] bg-[#FAF8F5] text-[#121212]">
                                    Kelas {{ ucfirst($schedule->class_type) }}
                                </span>
                            </div>

                            <!-- Availability Badge -->
                            <div>
                                @if($isAvailable)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Tersedia (Sisa {{ $seatsLeft }} Kursi)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#EFECE3] text-[#5E5C56] border border-[#DAD6CD]">
                                        Tiket Habis / Tidak Tersedia
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Mid Row: Schedule Timeline & Details -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center py-2">
                            
                            <!-- Departure -->
                            <div class="md:col-span-3">
                                <div class="text-3xl font-bold text-[#121212] tracking-tight">
                                    {{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }}
                                </div>
                                <div class="text-sm font-medium text-[#121212] mt-0.5">
                                    {{ $schedule->originStation->name ?? 'Asal' }} ({{ $schedule->originStation->code ?? '-' }})
                                </div>
                                <div class="text-xs text-[#5E5C56]">
                                    {{ \Carbon\Carbon::parse($schedule->travel_date)->translatedFormat('d M Y') }}
                                </div>
                            </div>

                            <!-- Duration & Route Line -->
                            <div class="md:col-span-3 text-center">
                                <span class="text-xs text-[#5E5C56] block mb-1.5">{{ $schedule->duration }} Langsung</span>
                                <div class="flex items-center justify-center">
                                    <span class="w-2 h-2 rounded-full border border-[#121212] bg-white"></span>
                                    <span class="flex-1 border-b border-dashed border-[#DAD6CD] mx-2"></span>
                                    <span class="w-2 h-2 rounded-full bg-[#121212]"></span>
                                </div>
                                <div class="mt-2 text-xs text-[#5E5C56]">
                                    WiFi • AC • Stopkontak
                                </div>
                            </div>

                            <!-- Arrival -->
                            <div class="md:col-span-3">
                                <div class="text-3xl font-bold text-[#121212] tracking-tight">
                                    {{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }}
                                </div>
                                <div class="text-sm font-medium text-[#121212] mt-0.5">
                                    {{ $schedule->destinationStation->name ?? 'Tujuan' }} ({{ $schedule->destinationStation->code ?? '-' }})
                                </div>
                                <div class="text-xs text-[#5E5C56]">
                                    {{ \Carbon\Carbon::parse($schedule->travel_date)->translatedFormat('d M Y') }}
                                </div>
                            </div>

                            <!-- Price & Action -->
                            <div class="md:col-span-3 flex flex-col md:items-end justify-center border-t md:border-t-0 pt-4 md:pt-0 border-[#EFECE3]">
                                <div class="mb-3 text-left md:text-right">
                                    <div class="text-2xl font-bold text-[#121212] tracking-tight">
                                        Rp {{ number_format($schedule->base_price, 0, ',', '.') }}
                                        <span class="text-xs font-normal text-[#5E5C56]">/orang</span>
                                    </div>
                                </div>

                                @if($isAvailable)
                                    <a href="{{ route('booking.seats', ['schedule' => $schedule->id, 'passengers' => $passengers]) }}" 
                                       class="group w-full md:w-auto inline-flex items-center justify-center gap-1.5 px-6 py-2.5 rounded-xl text-sm font-medium bg-[#121212] text-white hover:bg-black transition shadow-xs btn-press">
                                        <span>Pilih Kursi</span>
                                        <span class="arrow-slide">&rarr;</span>
                                    </a>
                                @else
                                    <button type="button" disabled 
                                            class="w-full md:w-auto px-6 py-2.5 rounded-xl text-sm font-medium bg-[#EFECE3] text-[#5E5C56] cursor-not-allowed border border-[#DAD6CD]">
                                        Habis
                                    </button>
                                @endif
                            </div>

                        </div>

                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-12 text-center">
            <a href="{{ route('home') }}" class="text-sm font-medium text-[#5E5C56] hover:text-[#121212] transition">
                &larr; Kembali ke Beranda
            </a>
        </div>

    </div>
</div>
@endsection
