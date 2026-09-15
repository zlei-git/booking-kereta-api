@extends('layouts.app')

@section('title', 'Jadwal & Tarif Kereta Api — NordicRail')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-navy-900">Jadwal & Tarif Kereta Api</h1>
        <p class="text-slate-600 text-sm mt-1">Cari dan bandingkan jam keberangkatan serta ketersediaan kelas kereta api antarkota.</p>
    </div>

    <!-- Filter Form (Clean, No Icons) -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 mb-10 shadow-sm card-60fps reveal-init">
        <form action="{{ route('schedules') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Stasiun Asal</label>
                    <select name="origin" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition">
                        <option value="">Semua Stasiun Asal</option>
                        @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ (request('origin') == $station->id || request('origin_station_id') == $station->id) ? 'selected' : '' }}>
                                {{ $station->name }} ({{ $station->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Stasiun Tujuan</label>
                    <select name="destination" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition">
                        <option value="">Semua Stasiun Tujuan</option>
                        @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ (request('destination') == $station->id || request('destination_station_id') == $station->id) ? 'selected' : '' }}>
                                {{ $station->name }} ({{ $station->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal</label>
                    <input type="date" name="date" value="{{ request('date') ?? request('travel_date') }}" min="{{ date('Y-m-d') }}" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Kelas</label>
                    <select name="class" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-800 focus:bg-white focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition">
                        <option value="">Semua Kelas</option>
                        <option value="ekonomi" {{ request('class') == 'ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                        <option value="bisnis" {{ request('class') == 'bisnis' ? 'selected' : '' }}>Bisnis</option>
                        <option value="eksekutif" {{ request('class') == 'eksekutif' ? 'selected' : '' }}>Eksekutif</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full bg-navy-900 hover:bg-navy-800 text-white font-bold py-2.5 px-4 rounded-xl transition text-sm text-center btn-press">
                        Cari Jadwal
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Area -->
    <div>
        @if(!$searched)
            <div class="text-center py-16 bg-white rounded-2xl border border-slate-200 shadow-sm max-w-xl mx-auto p-8">
                <span class="font-serif text-2xl font-bold text-navy-900 block mb-2">Pilih Filter Jadwal</span>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Gunakan filter stasiun asal, tujuan, atau tanggal di atas untuk melihat ketersediaan jadwal perjalanan kereta api.
                </p>
            </div>
        @elseif($schedules->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-slate-200 shadow-sm max-w-xl mx-auto p-8">
                <span class="font-serif text-2xl font-bold text-navy-900 block mb-2">Jadwal Tidak Ditemukan</span>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Tidak ada jadwal perjalanan kereta api yang cocok dengan kriteria pencarian Anda. Silakan coba tanggal atau rute stasiun lain.
                </p>
            </div>
        @else
            <div class="mb-5 flex justify-between items-center">
                <p class="text-sm font-semibold text-slate-700">Ditemukan {{ $schedules->total() }} jadwal keberangkatan</p>
            </div>
            
            <div class="space-y-4">
                @foreach($schedules as $schedule)
                    <div class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6 transition hover:border-slate-300 shadow-sm card-60fps reveal-init delay-{{ ($loop->index % 4 + 1) * 75 }}">
                        <div class="flex flex-col lg:flex-row gap-6 items-center justify-between">
                            <!-- Train Info -->
                            <div class="w-full lg:w-1/4">
                                <h3 class="font-serif text-lg font-bold text-navy-900">{{ $schedule->train->name ?? 'Kereta Api' }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $schedule->train->number ?? '-' }}</p>
                                <span class="inline-block mt-2 px-2.5 py-0.5 text-xs font-semibold rounded bg-slate-100 text-slate-700 uppercase tracking-wider">
                                    Kelas {{ ucfirst($schedule->class_type ?? 'Eksekutif') }}
                                </span>
                            </div>
                            
                            <!-- Times & Route -->
                            <div class="w-full lg:w-2/4 flex justify-between items-center px-2">
                                <div class="text-left">
                                    <p class="text-2xl font-bold text-navy-900">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }}</p>
                                    <p class="text-sm font-medium text-slate-800">{{ $schedule->originStation->name ?? 'Asal' }}</p>
                                    <p class="text-xs text-slate-400">{{ $schedule->originStation->code ?? '' }}</p>
                                </div>
                                
                                <div class="flex-1 px-6 text-center">
                                    <p class="text-xs font-semibold text-slate-500 mb-1.5">{{ $schedule->duration ?? '0j 0m' }}</p>
                                    <div class="w-full border-t border-slate-200 my-1"></div>
                                    <p class="text-xs text-slate-500 mt-1">{{ \Carbon\Carbon::parse($schedule->travel_date)->format('d M Y') }}</p>
                                </div>
                                
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-navy-900">{{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }}</p>
                                    <p class="text-sm font-medium text-slate-800">{{ $schedule->destinationStation->name ?? 'Tujuan' }}</p>
                                    <p class="text-xs text-slate-400">{{ $schedule->destinationStation->code ?? '' }}</p>
                                </div>
                            </div>
                            
                            <!-- Price & Action -->
                            <div class="w-full lg:w-1/4 flex flex-row lg:flex-col justify-between items-center lg:items-end border-t lg:border-t-0 lg:border-l border-slate-100 pt-4 lg:pt-0 lg:pl-6">
                                <div class="text-left lg:text-right">
                                    <p class="text-xs text-slate-400 uppercase font-semibold">Tarif Dasar</p>
                                    <p class="text-xl font-bold text-accent-600">Rp{{ number_format($schedule->base_price, 0, ',', '.') }}</p>
                                    <p class="text-xs text-slate-500">Sisa {{ $schedule->getAvailableSeatsCount() }} kursi</p>
                                </div>
                                
                                <div class="mt-0 lg:mt-3">
                                    <a href="{{ route('booking.seats', $schedule) }}?passengers=1" 
                                       class="inline-block bg-accent-600 hover:bg-accent-700 text-white font-bold px-5 py-2 rounded-xl text-xs transition btn-press">
                                        Pesan Tiket &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $schedules->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
