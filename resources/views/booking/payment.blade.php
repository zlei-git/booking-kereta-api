@extends('layouts.app')

@section('title', 'Metode Pembayaran — NordicRail')

@section('content')
<div class="bg-[#FCFBF8] min-h-screen py-10" x-data="{ method: '' }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#121212] mb-2">Pilih Metode Pembayaran</h1>
            <p class="text-sm text-[#5E5C56]">Transaksi aman dengan enkripsi standar perbankan.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left: Method Options -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-2xl border border-[#DAD6CD] p-6 sm:p-8 shadow-xs">
                    
                    <form action="{{ route('booking.processPayment', $booking->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_method" :value="method">

                        <div class="space-y-4 mb-8">
                            
                            <!-- Virtual Account -->
                            <label class="flex items-start p-4 rounded-xl border cursor-pointer transition"
                                   :class="method === 'virtual_account' ? 'border-[#121212] bg-[#F5F2EA]' : 'border-[#DAD6CD] hover:border-[#8B887F] bg-white'">
                                <div class="pt-0.5">
                                    <input type="radio" name="method_option" value="virtual_account" x-model="method" class="w-4 h-4 text-[#121212] focus:ring-[#121212] border-[#DAD6CD]">
                                </div>
                                <div class="ml-4 flex-1 text-xs">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-serif text-base font-bold text-[#121212]">Virtual Account Otomatis</span>
                                        <span class="text-xs px-2 py-0.5 rounded bg-white border border-[#DAD6CD] text-[#5E5C56]">BCA, Mandiri, BNI, BRI</span>
                                    </div>
                                    <p class="text-[#5E5C56] leading-relaxed">
                                        Nomor akun virtual unik yang terverifikasi secara instan tanpa perlu mengunggah bukti transfer.
                                    </p>
                                </div>
                            </label>

                            <!-- QRIS -->
                            <label class="flex items-start p-4 rounded-xl border cursor-pointer transition"
                                   :class="method === 'qris' ? 'border-[#121212] bg-[#F5F2EA]' : 'border-[#DAD6CD] hover:border-[#8B887F] bg-white'">
                                <div class="pt-0.5">
                                    <input type="radio" name="method_option" value="qris" x-model="method" class="w-4 h-4 text-[#121212] focus:ring-[#121212] border-[#DAD6CD]">
                                </div>
                                <div class="ml-4 flex-1 text-xs">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-serif text-base font-bold text-[#121212]">QRIS (Quick Response)</span>
                                        <span class="text-xs px-2 py-0.5 rounded bg-white border border-[#DAD6CD] text-[#5E5C56]">Semua Aplikasi Pembayaran</span>
                                    </div>
                                    <p class="text-[#5E5C56] leading-relaxed">
                                        Pindai kode QR dari aplikasi m-banking atau dompet digital apa pun dengan standar nasional QRIS.
                                    </p>
                                </div>
                            </label>

                            <!-- Kartu Kredit / Debit -->
                            <label class="flex items-start p-4 rounded-xl border cursor-pointer transition"
                                   :class="method === 'card' ? 'border-[#121212] bg-[#F5F2EA]' : 'border-[#DAD6CD] hover:border-[#8B887F] bg-white'">
                                <div class="pt-0.5">
                                    <input type="radio" name="method_option" value="card" x-model="method" class="w-4 h-4 text-[#121212] focus:ring-[#121212] border-[#DAD6CD]">
                                </div>
                                <div class="ml-4 flex-1 text-xs">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-serif text-base font-bold text-[#121212]">Kartu Kredit / Debit</span>
                                        <span class="text-xs px-2 py-0.5 rounded bg-white border border-[#DAD6CD] text-[#5E5C56]">Visa &bull; Mastercard &bull; JCB</span>
                                    </div>
                                    <p class="text-[#5E5C56] leading-relaxed">
                                        Pembayaran langsung dengan kartu debit atau kartu kredit yang mendukung verifikasi 3D Secure.
                                    </p>
                                </div>
                            </label>

                            <!-- E-Wallet -->
                            <label class="flex items-start p-4 rounded-xl border cursor-pointer transition"
                                   :class="method === 'ewallet' ? 'border-[#121212] bg-[#F5F2EA]' : 'border-[#DAD6CD] hover:border-[#8B887F] bg-white'">
                                <div class="pt-0.5">
                                    <input type="radio" name="method_option" value="ewallet" x-model="method" class="w-4 h-4 text-[#121212] focus:ring-[#121212] border-[#DAD6CD]">
                                </div>
                                <div class="ml-4 flex-1 text-xs">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-serif text-base font-bold text-[#121212]">Dompet Digital (E-Wallet)</span>
                                        <span class="text-xs px-2 py-0.5 rounded bg-white border border-[#DAD6CD] text-[#5E5C56]">GoPay, OVO, DANA</span>
                                    </div>
                                    <p class="text-[#5E5C56] leading-relaxed">
                                        Konfirmasi transaksi langsung dari aplikasi dompet digital ponsel Anda.
                                    </p>
                                </div>
                            </label>

                            <!-- Transfer Bank -->
                            <label class="flex items-start p-4 rounded-xl border cursor-pointer transition"
                                   :class="method === 'bank_transfer' ? 'border-[#121212] bg-[#F5F2EA]' : 'border-[#DAD6CD] hover:border-[#8B887F] bg-white'">
                                <div class="pt-0.5">
                                    <input type="radio" name="method_option" value="bank_transfer" x-model="method" class="w-4 h-4 text-[#121212] focus:ring-[#121212] border-[#DAD6CD]">
                                </div>
                                <div class="ml-4 flex-1 text-xs">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-serif text-base font-bold text-[#121212]">Transfer Bank Rekening Resmi</span>
                                        <span class="text-xs px-2 py-0.5 rounded bg-white border border-[#DAD6CD] text-[#5E5C56]">Bank NordicRail</span>
                                    </div>
                                    <p class="text-[#5E5C56] leading-relaxed">
                                        Transfer manual ke rekening operasional resmi PT NordicRail Indonesia.
                                    </p>
                                </div>
                            </label>

                        </div>

                        <div class="text-right">
                            <button type="submit" 
                                    :disabled="!method"
                                    class="w-full sm:w-auto px-10 py-3.5 rounded-xl text-sm font-semibold text-white transition shadow-xs"
                                    :class="method ? 'bg-[#121212] hover:bg-black' : 'bg-[#DAD6CD] text-[#8B887F] cursor-not-allowed'">
                                Lanjutkan Pembayaran &rarr;
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <!-- Right: Booking Brief -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-2xl border border-[#DAD6CD] p-6 shadow-xs sticky top-24">
                    <h2 class="font-serif text-lg font-bold text-[#121212] pb-4 mb-4 border-b border-[#EFECE3]">
                        Ringkasan Tagihan
                    </h2>

                    <div class="space-y-3 text-xs text-[#5E5C56] mb-6">
                        <div>
                            <span class="text-xs text-[#5E5C56] block">Kode Booking</span>
                            <span class="font-mono text-sm font-bold text-[#121212]">{{ $booking->booking_number }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-[#5E5C56] block">Rute & Kereta</span>
                            <span class="font-bold text-[#121212]">{{ $booking->schedule->train->name }} ({{ $booking->schedule->train->number }})</span>
                            <span class="block text-[#5E5C56]">{{ $booking->schedule->originStation->city }} &rarr; {{ $booking->schedule->destinationStation->city }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-[#5E5C56] block">Jadwal Keberangkatan</span>
                            <span class="text-[#121212]">{{ \Carbon\Carbon::parse($booking->schedule->travel_date)->translatedFormat('d M Y') }} &bull; {{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }}</span>
                        </div>
                    </div>

                    <div class="border-t border-[#EFECE3] pt-4 flex justify-between items-baseline">
                        <span class="font-serif text-sm font-bold text-[#121212]">Total yang Dibayar</span>
                        <span class="font-mono text-xl font-bold text-[#121212]">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="mt-6 pt-4 border-t border-[#EFECE3] text-center">
                        <a href="{{ route('booking.summary', $booking->id) }}" class="text-xs text-[#5E5C56] hover:text-[#121212]">
                            &larr; Kembali ke Ringkasan
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
