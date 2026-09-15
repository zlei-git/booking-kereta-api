@extends('layouts.app')

@section('title', 'Instruksi Pembayaran — NordicRail')

@section('content')
<div class="bg-[#FCFBF8] min-h-screen py-10">
    <div class="max-w-xl mx-auto px-4 sm:px-6">
        
        <div class="bg-white rounded-3xl border border-[#DAD6CD] overflow-hidden shadow-xs">
            
            <!-- Process Header -->
            <div class="bg-[#121212] p-8 text-center text-white">
                <h1 class="font-serif text-2xl sm:text-3xl font-bold mb-3">
                    Selesaikan Pembayaran
                </h1>
                <p class="text-sm text-[#DAD6CD]">
                    No. Pesanan: <strong class="text-white">{{ $booking->booking_number }}</strong>
                </p>

                <div class="mt-6 pt-6 border-t border-[#2C2A26] flex items-center justify-between">
                    <span class="text-xs text-[#8B887F]">Total Tagihan:</span>
                    <span class="font-mono text-2xl font-bold text-[#B7A07A]">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                
                @if($booking->payment->method === 'qris')
                    <!-- QRIS Mode -->
                    <div class="text-center">
                        <h2 class="font-serif text-lg font-bold text-[#121212] mb-2">Scan QR Code untuk Pembayaran</h2>
                        <p class="text-xs text-[#5E5C56] mb-6">Buka aplikasi mobile banking atau dompet digital Anda dan arahkan kamera ke kode QR berikut.</p>
                        
                        <div class="inline-block p-4 rounded-2xl border border-[#DAD6CD] bg-white shadow-xs mb-4">
                            <!-- Pure SVG/CSS QR representation without external images -->
                            <div class="w-48 h-48 bg-[#121212] p-2 rounded-xl grid grid-cols-6 grid-rows-6 gap-1">
                                @for($i = 0; $i < 36; $i++)
                                    <div class="{{ in_array($i, [0,1,6,7, 4,5,10,11, 24,25,30,31, 14,15,20,21]) ? 'bg-white' : ($i % 3 === 0 ? 'bg-white' : 'bg-[#121212]') }} rounded-xs"></div>
                                @endfor
                            </div>
                        </div>

                        <p class="font-mono text-xs text-[#8B887F] mb-6">
                            NMID: ID102026NRD09 &bull; Ref: {{ $booking->payment->reference_number ?? 'REF-NR' }}
                        </p>
                    </div>

                @elseif($booking->payment->method === 'virtual_account')
                    <!-- Virtual Account Mode -->
                    <div class="text-center" x-data="{
                        vaNumber: '{{ $booking->payment->va_number ?? '8800' . $booking->booking_number }}',
                        copied: false
                    }">
                        <h2 class="font-serif text-lg font-bold text-[#121212] mb-2">Nomor Virtual Account</h2>
                        <p class="text-xs text-[#5E5C56] mb-6">Gunakan menu transfer Virtual Account pada ATM, Mobile Banking, atau Internet Banking pilihan Anda.</p>

                        <div class="bg-[#F5F2EA] border border-[#DAD6CD] rounded-2xl p-6 mb-6">
                            <span class="text-xs text-[#5E5C56] block mb-2 font-medium">
                                Bank Rekanan NordicRail (BCA / Mandiri / BNI)
                            </span>
                            <div class="flex items-center justify-center gap-3">
                                <span class="font-mono text-2xl sm:text-3xl font-bold text-[#121212] tracking-wider" x-text="vaNumber"></span>
                                <button type="button" 
                                        @click="navigator.clipboard.writeText(vaNumber); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="px-3 py-1.5 rounded-lg border border-[#DAD6CD] bg-white text-xs font-semibold text-[#121212] hover:bg-[#FAF8F5] transition">
                                    <span x-show="!copied">Salin</span>
                                    <span x-show="copied" style="display: none;">Tersalin</span>
                                </button>
                            </div>
                        </div>

                        <p class="text-xs text-[#5E5C56] mb-6">
                            Transaksi diverifikasi secara otomatis dalam beberapa detik setelah dana diterima.
                        </p>
                    </div>

                @elseif($booking->payment->method === 'card')
                    <!-- Card Mode -->
                    <div>
                        <h2 class="font-serif text-lg font-bold text-[#121212] mb-2 text-center">Data Kartu Kredit / Debit</h2>
                        <p class="text-xs text-[#5E5C56] mb-6 text-center">Masukkan informasi kartu yang tertera pada bagian depan dan belakang kartu Anda.</p>

                        <div class="space-y-4 mb-6 text-xs">
                            <div>
                                <label class="block font-medium text-sm text-[#121212] mb-1">Nomor Kartu</label>
                                <input type="text" placeholder="4000 1234 5678 9010" value="4111 2222 3333 4444" class="w-full font-mono rounded-xl border border-[#DAD6CD] bg-[#FCFBF8] py-2.5 px-3 text-sm">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-medium text-sm text-[#121212] mb-1">Masa Berlaku (MM/YY)</label>
                                    <input type="text" placeholder="12/28" value="12/28" class="w-full font-mono rounded-xl border border-[#DAD6CD] bg-[#FCFBF8] py-2.5 px-3 text-sm">
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-[#121212] mb-1">Kode CVV</label>
                                    <input type="password" placeholder="123" value="123" class="w-full font-mono rounded-xl border border-[#DAD6CD] bg-[#FCFBF8] py-2.5 px-3 text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-[#121212] mb-1">Nama Pemegang Kartu</label>
                                <input type="text" value="{{ auth()->user()->name }}" class="w-full rounded-xl border border-[#DAD6CD] bg-[#FCFBF8] py-2.5 px-3 text-sm">
                            </div>
                        </div>
                    </div>

                @elseif($booking->payment->method === 'ewallet')
                    <!-- E-Wallet Mode -->
                    <div class="text-center">
                        <h2 class="font-serif text-lg font-bold text-[#121212] mb-2">Konfirmasi Dompet Digital</h2>
                        <p class="text-xs text-[#5E5C56] mb-6">Notifikasi pembayaran telah dikirimkan ke aplikasi dompet digital nomor terdaftar: <strong>{{ auth()->user()->phone ?? '081234567890' }}</strong>.</p>

                        <div class="bg-[#F5F2EA] border border-[#DAD6CD] rounded-2xl p-6 mb-6">
                            <span class="text-xs text-[#5E5C56] block mb-1">Layanan Pembayaran</span>
                            <span class="font-serif text-lg font-bold text-[#121212] block">GoPay / OVO / DANA</span>
                            <span class="text-xs text-[#5E5C56] mt-2 block">Ref: {{ $booking->payment->reference_number ?? 'REF-EWALLET' }}</span>
                        </div>
                    </div>

                @else
                    <!-- Bank Transfer Mode -->
                    <div class="text-center">
                        <h2 class="font-serif text-lg font-bold text-[#121212] mb-2">Transfer Rekening Resmi</h2>
                        <p class="text-xs text-[#5E5C56] mb-6">Lakukan transfer persis hingga nominal terakhir ke rekening giro operasional kami.</p>

                        <div class="bg-[#F5F2EA] border border-[#DAD6CD] rounded-2xl p-6 mb-6 text-left space-y-3 text-xs">
                            <div class="flex justify-between pb-2 border-b border-[#DAD6CD]">
                                <span class="text-[#8B887F]">Bank Tujuan:</span>
                                <strong class="text-[#121212]">Bank NordicRail Indonesia</strong>
                            </div>
                            <div class="flex justify-between pb-2 border-b border-[#DAD6CD]">
                                <span class="text-[#8B887F]">Nomor Rekening:</span>
                                <strong class="font-mono text-base text-[#121212]">102-00-998877-1</strong>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#8B887F]">Nama Penerima:</span>
                                <strong class="text-[#121212]">PT NordicRail Indonesia</strong>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Payment Confirmation Action -->
                <form action="{{ route('booking.confirmPayment', $booking->id) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full py-3.5 rounded-xl text-sm font-semibold bg-[#121212] text-white hover:bg-black transition shadow-xs text-center">
                        Saya Sudah Membayar &bull; Konfirmasi Pembayaran &rarr;
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('orders.index') }}" class="text-xs text-[#5E5C56] hover:text-[#121212] transition">
                        Bayar Nanti (Buka Pesanan Saya)
                    </a>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
