@extends('layouts.app')

@section('title', 'Pemesanan Dikonfirmasi — NordicRail')

@section('content')
<div class="bg-[#FCFBF8] min-h-screen py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        
        <div class="bg-white rounded-3xl border border-[#DAD6CD] overflow-hidden shadow-xs">
            
            <!-- Celebration Header -->
            <div class="bg-[#121212] text-white p-8 text-center">
                <h1 class="font-serif text-3xl font-bold mb-2">
                    Pemesanan Berhasil Dikonfirmasi
                </h1>
                <p class="text-sm text-[#DAD6CD] max-w-sm mx-auto">
                    Terima kasih telah memilih NordicRail. Pembayaran tiket Anda telah terverifikasi secara penuh.
                </p>

                <!-- Booking Code Box -->
                <div class="mt-6 pt-6 border-t border-[#2C2A26] inline-block">
                    <span class="text-xs text-[#DAD6CD] block mb-1">
                        Nomor Booking
                    </span>
                    <div class="font-mono text-3xl font-bold tracking-widest text-white px-5 py-2 rounded-xl bg-[#1C1B19] border border-[#2C2A26] inline-block">
                        {{ $booking->booking_number }}
                    </div>
                </div>
            </div>

            <!-- Content Details -->
            <div class="p-6 sm:p-8 space-y-6 text-xs text-[#5E5C56]">
                
                <!-- Trip Summary -->
                <div class="bg-[#F5F2EA] border border-[#DAD6CD] rounded-2xl p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="font-serif text-lg font-bold text-[#121212] block">{{ $booking->schedule->train->name ?? 'Kereta Api' }}</span>
                            <span class="font-mono text-xs text-[#8B887F]">{{ $booking->schedule->train->number }} &bull; Kelas {{ ucfirst($booking->schedule->class_type) }}</span>
                        </div>
                        <span class="font-mono text-xs text-[#121212] font-semibold">
                            {{ \Carbon\Carbon::parse($booking->schedule->travel_date)->translatedFormat('l, d F Y') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between border-t border-[#DAD6CD] pt-3">
                        <div>
                            <span class="font-mono text-base font-bold text-[#121212]">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }}</span>
                            <span class="block text-[#121212] font-medium">{{ $booking->schedule->originStation->name }}</span>
                        </div>
                        <div class="text-[#8B887F] text-center">
                            <span>&rarr;</span>
                        </div>
                        <div class="text-right">
                            <span class="font-mono text-base font-bold text-[#121212]">{{ \Carbon\Carbon::parse($booking->schedule->arrival_time)->format('H:i') }}</span>
                            <span class="block text-[#121212] font-medium">{{ $booking->schedule->destinationStation->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Passengers -->
                <div>
                    <h2 class="font-serif text-base font-bold text-[#121212] mb-3">Penumpang & Kursi</h2>
                    <div class="space-y-2">
                        @foreach($booking->passengers as $p)
                            <div class="flex justify-between items-center p-3 rounded-xl border border-[#DAD6CD] bg-[#FCFBF8]">
                                <div>
                                    <span class="font-bold text-[#121212] block">{{ $p->name }}</span>
                                    <span class="text-[#8B887F]">{{ $p->id_type ?? 'KTP' }}: {{ $p->identity_number }} &bull; {{ ucfirst($p->passenger_type) }}</span>
                                </div>
                                <span class="font-mono font-bold text-[#121212] bg-[#F5F2EA] px-2.5 py-1 rounded border border-[#DAD6CD]">
                                    Kursi {{ $p->seat->seat_number ?? '-' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="border-t border-[#EFECE3] pt-4 space-y-1.5">
                    <div class="flex justify-between">
                        <span>Metode Pembayaran:</span>
                        <strong class="text-[#121212] font-mono uppercase">{{ $booking->payment->method_label ?? ($booking->payment->method ?? 'Lunas') }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Terbayar:</span>
                        <strong class="text-[#121212] font-mono text-sm">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Status Transaksi:</span>
                        <span class="text-emerald-800 font-semibold uppercase">Lunas / Terverifikasi</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('orders.ticket', $booking->id) }}" 
                       class="flex-1 py-3 px-4 rounded-xl text-xs font-semibold uppercase tracking-wider bg-[#121212] text-white hover:bg-black transition text-center shadow-xs">
                        Buka & Cetak E-Tiket &rarr;
                    </a>
                    <a href="{{ route('orders.index') }}" 
                       class="flex-1 py-3 px-4 rounded-xl text-xs font-semibold uppercase tracking-wider border border-[#DAD6CD] bg-[#F5F2EA] text-[#121212] hover:bg-white transition text-center">
                        Lihat Pesanan Saya
                    </a>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
