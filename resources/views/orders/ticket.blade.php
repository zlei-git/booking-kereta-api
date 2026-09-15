@extends('layouts.app')

@section('title', 'E-Tiket Boarding Pass — NordicRail')

@section('content')
<style>
    @media print {
        header, footer, nav, .no-print {
            display: none !important;
        }
        body {
            background-color: white !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .print-ticket {
            box-shadow: none !important;
            border: 1px solid #121212 !important;
            margin: 0 auto !important;
            width: 100% !important;
            max-width: 100% !important;
        }
    }
</style>

<div class="bg-[#FCFBF8] min-h-screen py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        
        <!-- Utility Actions (Hidden when printing) -->
        <div class="mb-6 flex justify-between items-center no-print text-xs">
            <a href="{{ route('orders.index') }}" class="font-semibold text-[#5E5C56] hover:text-[#121212] transition">
                &larr; Kembali ke Pesanan Saya
            </a>
            <div class="flex items-center gap-3">
                <button type="button" 
                        onclick="window.print()" 
                        class="px-5 py-2.5 rounded-xl text-xs font-semibold bg-[#121212] text-white hover:bg-black transition shadow-xs">
                    Cetak E-Tiket (Print)
                </button>
            </div>
        </div>

        <!-- Visual Boarding Pass Preview (Hidden on print) -->
        <div class="mb-8 rounded-3xl overflow-hidden border border-[#DAD6CD] bg-white p-2 shadow-xs no-print">
            <div class="rounded-2xl overflow-hidden aspect-[16/9] bg-[#EFECE3] relative">
                <img src="{{ asset('images/ticket_mockup.jpg') }}" 
                     alt="NordicRail Official Boarding Pass" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                <div class="absolute bottom-4 left-5 right-5 text-white flex justify-between items-end">
                    <div>
                        <p class="font-serif text-lg font-bold">Boarding Pass Resmi NordicRail</p>
                        <p class="text-xs text-[#DAD6CD]">Simpan atau cetak untuk ditunjukkan kepada petugas boarding stasiun.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boarding Pass Card -->
        <div class="print-ticket bg-white rounded-3xl border border-[#DAD6CD] overflow-hidden shadow-sm">
            
            <!-- Header Strip -->
            <div class="bg-[#121212] text-white p-6 sm:p-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <span class="text-xs text-[#B7A07A] block font-medium">
                        BOARDING PASS E-TIKET
                    </span>
                    <span class="font-serif text-2xl sm:text-3xl font-bold tracking-tight">NordicRail</span>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-xs text-[#DAD6CD] block">Kode Booking</span>
                    <span class="font-mono text-2xl font-bold tracking-widest text-white">{{ $booking->booking_number }}</span>
                </div>
            </div>

            <!-- Ticket Body -->
            <div class="p-6 sm:p-8">
                
                <!-- Train & Schedule Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 mb-6 border-b border-[#DAD6CD] gap-4">
                    <div>
                        <span class="font-serif text-2xl font-bold text-[#121212] block">
                            {{ $booking->schedule->train->name ?? 'Kereta Api' }}
                        </span>
                        <span class="text-sm text-[#5E5C56]">
                            Nomor KA {{ $booking->schedule->train->number }} &bull; Kelas {{ ucfirst($booking->schedule->class_type) }}
                        </span>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="text-xs text-[#5E5C56] block">Tanggal Perjalanan</span>
                        <span class="font-mono text-sm font-bold text-[#121212]">
                            {{ \Carbon\Carbon::parse($booking->schedule->travel_date)->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                </div>

                <!-- Departure & Arrival Journey Map -->
                <div class="bg-[#F5F2EA] border border-[#DAD6CD] rounded-2xl p-6 mb-8 grid grid-cols-1 sm:grid-cols-11 gap-4 items-center">
                    
                    <div class="sm:col-span-5">
                        <span class="text-xs text-[#5E5C56] block">Keberangkatan</span>
                        <span class="font-mono text-3xl font-bold text-[#121212]">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }}</span>
                        <span class="font-serif text-base font-bold text-[#121212] block mt-0.5">{{ $booking->schedule->originStation->name }} ({{ $booking->schedule->originStation->code }})</span>
                        <span class="text-xs text-[#5E5C56]">{{ $booking->schedule->originStation->city }}</span>
                    </div>

                    <div class="sm:col-span-1 text-center font-mono text-[#8B887F] py-2 sm:py-0">
                        &rarr;
                    </div>

                    <div class="sm:col-span-5 text-left sm:text-right">
                        <span class="text-xs text-[#5E5C56] block">Kedatangan</span>
                        <span class="font-mono text-3xl font-bold text-[#121212]">{{ \Carbon\Carbon::parse($booking->schedule->arrival_time)->format('H:i') }}</span>
                        <span class="font-serif text-base font-bold text-[#121212] block mt-0.5">{{ $booking->schedule->destinationStation->name }} ({{ $booking->schedule->destinationStation->code }})</span>
                        <span class="text-xs text-[#5E5C56]">{{ $booking->schedule->destinationStation->city }}</span>
                    </div>

                </div>

                <!-- Passenger Roster -->
                <div class="mb-8">
                    <h2 class="font-serif text-base font-bold text-[#121212] pb-2 mb-4 border-b border-[#EFECE3]">
                        Daftar Penumpang Resmi ({{ $booking->passenger_count }} Orang)
                    </h2>

                    <div class="space-y-3">
                        @foreach($booking->passengers as $p)
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 rounded-xl border border-[#DAD6CD] bg-[#FCFBF8] gap-2">
                                <div>
                                    <span class="font-serif text-base font-bold text-[#121212] block">{{ $p->name }}</span>
                                    <span class="text-xs text-[#5E5C56]">
                                        {{ $p->id_type ?? 'KTP' }}: {{ $p->identity_number }} &bull; Tipe: {{ ucfirst($p->passenger_type) }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-4">
                                    <div class="text-left sm:text-right">
                                        <span class="text-xs text-[#5E5C56] block">Gerbong & Kursi</span>
                                        <span class="font-mono text-lg font-bold text-[#121212]">
                                            EKS-1 &bull; {{ $p->seat->seat_number ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Barcode & Boarding Notices -->
                <div class="pt-6 border-t border-[#DAD6CD] grid grid-cols-1 sm:grid-cols-12 gap-6 items-center">
                    
                    <div class="sm:col-span-8 space-y-1 text-xs text-[#5E5C56]">
                        <p class="font-semibold text-[#121212]">Ketentuan Boarding:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-xs text-[#5E5C56]">
                            <li>Tunjukkan e-tiket ini bersama kartu identitas asli saat pemeriksaan boarding.</li>
                            <li>Tiba di stasiun selambat-lambatnya 30 menit sebelum jadwal keberangkatan.</li>
                            <li>Pintu boarding stasiun ditutup 5 menit sebelum kereta diberangkatkan.</li>
                        </ul>
                    </div>

                    <!-- Typographic Barcode Simulation -->
                    <div class="sm:col-span-4 text-center">
                        <div class="inline-block p-2.5 bg-[#F5F2EA] rounded-xl border border-[#DAD6CD]">
                            <div class="h-10 w-44 flex items-center justify-center gap-0.5 mx-auto">
                                @for($i = 0; $i < 40; $i++)
                                    <span class="h-full {{ in_array($i % 5, [0, 2]) ? 'w-1 bg-[#121212]' : 'w-0.5 bg-[#5E5C56]' }}"></span>
                                @endfor
                            </div>
                            <span class="font-mono text-xs text-[#5E5C56] tracking-wider block mt-1">
                                {{ $booking->booking_number }}
                            </span>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Footer Strip -->
            <div class="bg-[#F5F2EA] border-t border-[#DAD6CD] px-6 sm:px-8 py-3.5 flex justify-between items-center text-xs text-[#5E5C56]">
                <span>PT NordicRail Indonesia &bull; Hak Cipta Dilindungi</span>
                <span class="font-semibold text-[#121212]">Status: Lunas &bull; Siap Boarding</span>
            </div>

        </div>

    </div>
</div>
@endsection
