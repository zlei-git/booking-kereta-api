@extends('layouts.app')

@section('title', 'Penawaran & Promosi — NordicRail')

@section('content')
<div class="bg-[#FCFBF8] min-h-screen py-12 sm:py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="max-w-2xl mb-12 animate-fade-in-up">
            <h1 class="font-serif text-3xl sm:text-5xl font-bold text-[#121212] tracking-tight mb-3">
                Promosi Perjalanan
            </h1>
            <p class="text-base text-[#5E5C56] leading-relaxed">
                Nikmati potongan tarif perjalanan kereta api dengan menggunakan kode promo resmi NordicRail saat menyelesaikan data pemesanan Anda.
            </p>
        </div>

        <!-- Promotions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
            @forelse($promotions as $promo)
                <div class="bg-white border border-[#DAD6CD] rounded-2xl p-6 flex flex-col justify-between shadow-xs card-60fps reveal-init delay-{{ ($loop->index % 3 + 1) * 100 }}"
                     x-data="{ copied: false }">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded border border-[#DAD6CD] bg-[#F5F2EA] text-[#121212]">
                                {{ $promo->discount_type === 'percentage' ? 'Diskon ' . $promo->value . '%' : 'Potongan Rp ' . number_format($promo->value, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-[#8B887F]">
                                Berlaku s/d {{ $promo->end_date ? \Carbon\Carbon::parse($promo->end_date)->translatedFormat('d M Y') : 'Selesai' }}
                            </span>
                        </div>

                        <h2 class="font-serif text-xl font-bold text-[#121212] mb-2">
                            {{ $promo->title }}
                        </h2>

                        <p class="text-xs text-[#5E5C56] leading-relaxed mb-6">
                            {{ $promo->description }}
                        </p>
                    </div>

                    <div>
                        <div class="border-t border-[#DAD6CD] pt-4 mb-4 text-xs text-[#5E5C56] space-y-1">
                            <p>Min. Transaksi: <strong class="text-[#121212]">Rp {{ number_format($promo->min_spend, 0, ',', '.') }}</strong></p>
                            @if($promo->max_discount)
                                <p>Maks. Diskon: <strong class="text-[#121212]">Rp {{ number_format($promo->max_discount, 0, ',', '.') }}</strong></p>
                            @endif
                        </div>

                        <!-- Promo Code & Copy Action -->
                        <div class="bg-[#F5F2EA] border border-[#DAD6CD] rounded-xl p-3 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-[#5E5C56] block">Kode Promo</span>
                                <span class="font-mono text-sm font-bold text-[#121212] tracking-wider">{{ $promo->code }}</span>
                            </div>
                            <button type="button"
                                    @click="navigator.clipboard.writeText('{{ $promo->code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition border border-[#DAD6CD]"
                                    :class="copied ? 'bg-[#121212] text-white' : 'bg-white text-[#121212] hover:bg-[#F5F2EA]'">
                                <span x-show="!copied">Salin Kode</span>
                                <span x-show="copied" style="display: none;">Tersalin</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 border border-[#DAD6CD] rounded-2xl bg-white">
                    <p class="text-sm text-[#5E5C56]">Belum ada promosi aktif saat ini. Silakan periksa kembali beberapa saat lagi.</p>
                </div>
            @endforelse
        </div>

        <!-- How to Use Promo Section -->
        <div class="border-t border-[#DAD6CD] pt-12">
            <h3 class="font-serif text-2xl font-bold text-[#121212] mb-6">
                Cara Menggunakan Kode Promo
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
                <div class="space-y-2">
                    <span class="font-serif text-lg font-bold text-[#8B887F] block">01</span>
                    <h4 class="font-semibold text-[#121212]">Pilih Rute & Kursi</h4>
                    <p class="text-xs text-[#5E5C56] leading-relaxed">
                        Cari jadwal keberangkatan kereta yang Anda inginkan, pilih jumlah penumpang, dan tentukan nomor kursi favorit di peta gerbong.
                    </p>
                </div>
                <div class="space-y-2">
                    <span class="font-serif text-lg font-bold text-[#8B887F] block">02</span>
                    <h4 class="font-semibold text-[#121212]">Masukkan Kode Saat Isi Data</h4>
                    <p class="text-xs text-[#5E5C56] leading-relaxed">
                        Pada formulir data penumpang, masukkan kode promo di kolom yang disediakan dan tekan tombol Terapkan untuk melihat Live Price baru.
                    </p>
                </div>
                <div class="space-y-2">
                    <span class="font-serif text-lg font-bold text-[#8B887F] block">03</span>
                    <h4 class="font-semibold text-[#121212]">Selesaikan Pembayaran</h4>
                    <p class="text-xs text-[#5E5C56] leading-relaxed">
                        Periksa rincian tarif hemat pada ringkasan pesanan, lalu selesaikan pembayaran melalui kanal transaksi yang dipilih.
                    </p>
                </div>
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold bg-[#121212] text-white hover:bg-black transition">
                    Cari Tiket Kereta Sekarang
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
