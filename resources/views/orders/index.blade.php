@extends('layouts.app')

@section('title', 'Pesanan Saya — NordicRail')

@section('content')
<div class="bg-[#FCFBF8] min-h-screen py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <span class="text-xs uppercase font-mono tracking-widest text-[#8B887F] block mb-1">Riwayat Transaksi</span>
            <h1 class="font-serif text-3xl font-bold text-[#121212]">Pesanan Saya</h1>
        </div>

        @if($bookings->isEmpty())
            <div class="bg-white p-12 rounded-2xl border border-[#DAD6CD] text-center shadow-xs">
                <h2 class="font-serif text-xl font-bold text-[#121212] mb-2">Belum Ada Riwayat Pesanan</h2>
                <p class="text-xs text-[#5E5C56] mb-6 max-w-sm mx-auto">
                    Anda belum memiliki riwayat reservasi tiket kereta api di akun NordicRail Anda.
                </p>
                <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-[#121212] rounded-xl text-xs font-semibold text-white hover:bg-black transition">
                    Cari Tiket Kereta &rarr;
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-2xl border border-[#DAD6CD] shadow-xs overflow-hidden">
                        
                        <!-- Header Strip -->
                        <div class="flex justify-between items-center px-6 py-3.5 border-b border-[#EFECE3] bg-[#F5F2EA] text-xs">
                            <div class="flex items-center gap-2 font-mono">
                                <span class="text-[#8B887F]">KODE:</span>
                                <strong class="text-[#121212]">{{ $booking->booking_number }}</strong>
                            </div>

                            <div>
                                @if($booking->status == 'pending')
                                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-amber-50 text-amber-900 border border-amber-200">Menunggu Pembayaran</span>
                                @elseif($booking->status == 'confirmed')
                                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-900 border border-emerald-200">Dikonfirmasi</span>
                                @elseif($booking->status == 'completed')
                                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-50 text-blue-900 border border-blue-200">Selesai</span>
                                @elseif($booking->status == 'cancelled')
                                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-rose-50 text-rose-900 border border-rose-200">Dibatalkan</span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex flex-col sm:flex-row justify-between gap-6">
                            <div class="space-y-2">
                                <div class="flex items-center gap-3">
                                    <h2 class="font-serif text-xl font-bold text-[#121212]">{{ $booking->schedule->train->name ?? 'Kereta Api' }}</h2>
                                    <span class="font-mono text-xs px-2 py-0.5 rounded bg-[#F5F2EA] border border-[#DAD6CD] text-[#5E5C56]">
                                        {{ $booking->schedule->train->number ?? '-' }}
                                    </span>
                                </div>
                                <p class="text-xs text-[#5E5C56]">
                                    {{ \Carbon\Carbon::parse($booking->schedule->travel_date)->translatedFormat('l, d F Y') }} &bull; Kelas {{ ucfirst($booking->schedule->class_type) }}
                                </p>

                                <div class="flex items-center gap-4 text-xs font-mono text-[#121212] pt-2">
                                    <div>
                                        <span class="font-bold text-base block">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }}</span>
                                        <span class="text-[#5E5C56] font-sans">{{ $booking->schedule->originStation->city }}</span>
                                    </div>
                                    <div class="text-[#8B887F]">&rarr;</div>
                                    <div>
                                        <span class="font-bold text-base block">{{ \Carbon\Carbon::parse($booking->schedule->arrival_time)->format('H:i') }}</span>
                                        <span class="text-[#5E5C56] font-sans">{{ $booking->schedule->destinationStation->city }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="sm:text-right flex flex-col justify-between border-t sm:border-t-0 pt-4 sm:pt-0 border-[#EFECE3]">
                                <div>
                                    <span class="text-xs text-[#5E5C56] block">Total Transaksi</span>
                                    <span class="font-mono text-lg font-bold text-[#121212]">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="pt-4 flex flex-col sm:flex-row sm:justify-end gap-2">
                                    @if(in_array($booking->status, ['confirmed', 'completed']))
                                        <a href="{{ route('orders.ticket', $booking) }}" 
                                           class="px-4 py-2 bg-[#121212] text-white rounded-xl text-xs font-semibold hover:bg-black transition text-center shadow-xs">
                                            Buka E-Tiket &rarr;
                                        </a>
                                    @elseif($booking->status == 'pending')
                                        <a href="{{ route('booking.payment', $booking) }}" 
                                           class="px-4 py-2 bg-[#121212] text-white rounded-xl text-xs font-semibold hover:bg-black transition text-center">
                                            Bayar Sekarang
                                        </a>
                                        <form action="{{ route('orders.cancel', $booking) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?');">
                                            @csrf
                                            <button type="submit" class="w-full px-3 py-2 bg-white text-rose-800 border border-[#DAD6CD] rounded-xl text-xs font-semibold hover:bg-rose-50 transition">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach

                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
