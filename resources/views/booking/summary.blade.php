@extends('layouts.app')

@section('title', 'Ringkasan Pesanan — NordicRail')

@section('content')
<div class="bg-[#FCFBF8] min-h-screen py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#121212] mb-2">Ringkasan Pesanan</h1>
            <p class="text-sm text-[#5E5C56]">
                Nomor Pesanan: <strong class="text-[#121212]">{{ $booking->booking_number }}</strong>
            </p>
        </div>

        @if($booking->expired_at)
            <div class="bg-[#F5F2EA] border border-[#DAD6CD] text-[#5E5C56] rounded-2xl p-4 mb-8 text-center text-xs">
                Selesaikan transaksi sebelum <strong class="text-[#121212]">{{ \Carbon\Carbon::parse($booking->expired_at)->format('d M Y H:i') }} WIB</strong> untuk menghindari pembatalan otomatis.
            </div>
        @endif

        <!-- Trip Card -->
        <div class="bg-white rounded-2xl border border-[#DAD6CD] p-6 sm:p-8 mb-6 shadow-xs">
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-[#EFECE3]">
                <div>
                    <h2 class="font-serif text-xl font-bold text-[#121212]">{{ $booking->schedule->train->name ?? 'Kereta Api' }}</h2>
                    <p class="text-xs text-[#5E5C56] mt-0.5">Layanan Perjalanan Kereta Api</p>
                </div>
                <div class="text-right">
                    <span class="text-xs px-2.5 py-1 rounded bg-[#F5F2EA] border border-[#DAD6CD] text-[#5E5C56] font-medium">
                        {{ $booking->schedule->train->number }}
                    </span>
                    <span class="text-xs font-semibold text-[#5E5C56] block mt-1">
                        Kelas {{ ucfirst($booking->schedule->class_type) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 items-center bg-[#F5F2EA] p-5 rounded-xl border border-[#DAD6CD] mb-4">
                <div>
                    <span class="text-xs text-[#5E5C56] block">Berangkat</span>
                    <span class="text-xl font-bold text-[#121212]">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }}</span>
                    <span class="text-xs text-[#5E5C56] block font-semibold">{{ $booking->schedule->originStation->name }}</span>
                    <span class="text-xs text-[#8B887F]">{{ $booking->schedule->originStation->city }}</span>
                </div>

                <div class="text-center">
                    <span class="text-xs text-[#5E5C56]">{{ $booking->schedule->duration }}</span>
                    <div class="text-[#8B887F]">&rarr;</div>
                    <span class="text-xs text-[#5E5C56]">{{ \Carbon\Carbon::parse($booking->schedule->travel_date)->translatedFormat('d M Y') }}</span>
                </div>

                <div class="text-right">
                    <span class="text-xs text-[#5E5C56] block">Tiba</span>
                    <span class="text-xl font-bold text-[#121212]">{{ \Carbon\Carbon::parse($booking->schedule->arrival_time)->format('H:i') }}</span>
                    <span class="text-xs text-[#5E5C56] block font-semibold">{{ $booking->schedule->destinationStation->name }}</span>
                    <span class="text-xs text-[#8B887F]">{{ $booking->schedule->destinationStation->city }}</span>
                </div>
            </div>
        </div>

        <!-- Passengers List Card -->
        <div class="bg-white rounded-2xl border border-[#DAD6CD] p-6 sm:p-8 mb-6 shadow-xs">
            <h2 class="font-serif text-lg font-bold text-[#121212] pb-4 mb-4 border-b border-[#EFECE3]">
                Daftar Penumpang ({{ $booking->passenger_count }} Orang)
            </h2>

            <div class="space-y-3">
                @foreach($booking->passengers as $passenger)
                    <div class="flex items-center justify-between p-3.5 bg-[#FCFBF8] border border-[#DAD6CD] rounded-xl text-xs">
                        <div>
                            <span class="font-semibold text-[#121212] text-sm block">{{ $passenger->name }}</span>
                            <span class="text-[#5E5C56]">
                                {{ $passenger->id_type ?? 'KTP' }}: {{ $passenger->identity_number }} &bull; {{ ucfirst($passenger->passenger_type) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-[#5E5C56] block">Nomor Kursi</span>
                            <span class="text-sm font-bold text-[#121212]">{{ $passenger->seat->seat_number ?? '-' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Price Breakdown Card -->
        <div class="bg-white rounded-2xl border border-[#DAD6CD] p-6 sm:p-8 mb-8 shadow-xs">
            <h2 class="font-serif text-lg font-bold text-[#121212] pb-4 mb-4 border-b border-[#EFECE3]">
                Rincian Tarif & Pembayaran
            </h2>

            <div class="space-y-2.5 text-xs text-[#5E5C56]">
                <div class="flex justify-between items-center">
                    <span>Tiket Kereta Dasar (x{{ $booking->passenger_count }} Penumpang):</span>
                    <span class="font-mono text-[#121212]">Rp {{ number_format($booking->base_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Biaya Layanan Sistem:</span>
                    <span class="font-mono text-[#121212]">Rp {{ number_format($booking->service_fee, 0, ',', '.') }}</span>
                </div>
                @if($booking->addon_fee > 0)
                    <div class="flex justify-between items-center">
                        <span>Paket Santap On-Board (Nordic Dining):</span>
                        <span class="font-mono text-[#121212]">Rp {{ number_format($booking->addon_fee, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($booking->discount_amount > 0)
                    <div class="flex justify-between items-center text-emerald-800 font-semibold">
                        <span>Potongan Promo ({{ $booking->promo_code }}):</span>
                        <span class="font-mono">- Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="border-t border-[#EFECE3] pt-4 mt-4 flex justify-between items-baseline">
                    <span class="font-serif text-base font-bold text-[#121212]">Total Pembayaran</span>
                    <span class="font-mono text-2xl font-bold text-[#121212]">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center">
            <a href="{{ route('booking.payment', $booking->id) }}" 
               class="inline-block w-full sm:w-auto px-10 py-3.5 rounded-xl text-sm font-semibold bg-[#121212] text-white hover:bg-black transition shadow-xs">
                Pilih Metode Pembayaran &rarr;
            </a>
        </div>

    </div>
</div>
@endsection
