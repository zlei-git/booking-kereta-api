@extends('layouts.app')

@section('title', 'Armada & Layanan — NordicRail')

@section('content')
<!-- HERO BANNER with Full Train Station Photo Background (Compact & Balanced) -->
<div class="relative bg-[#121212] text-white py-14 sm:py-16 lg:py-20 border-b border-[#2C2A26] overflow-hidden">
    <!-- Full-bleed background image of the train station -->
    <img src="{{ asset('images/train_station.jpg') }}" 
         alt="Stasiun Kereta Api NordicRail" 
         class="absolute inset-0 w-full h-full object-cover object-[center_35%]">
    
    <!-- Dark gradient overlay matching black theme for contrast & readability -->
    <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/75 to-black/60 sm:from-black/80 sm:via-black/70 sm:to-black/55"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-white leading-tight animate-fade-in-up">
                Armada & Layanan Kereta Api
            </h1>
            <p class="text-slate-200 text-sm sm:text-base font-normal mt-3 sm:mt-4 leading-relaxed animate-fade-in-up delay-100">
                Menghadirkan standar transportasi perkeretaapian modern yang dirancang untuk kenyamanan, ketenangan, dan kepuasan setiap penumpang di seluruh jaringan stasiun.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12">
    
    <!-- Class 1: Luxury & Panoramic -->
    <div class="bg-white rounded-2xl border border-slate-200 p-8 sm:p-10 card-60fps reveal-init">
        <div class="max-w-3xl">
            <span class="text-xs uppercase font-bold text-accent-600 tracking-wider">Kelas Tertinggi</span>
            <h2 class="font-serif text-3xl font-bold text-navy-900 mt-1 mb-4">Luxury Sleeper & Panoramic</h2>
            <p class="text-slate-600 leading-relaxed mb-6">
                Dirancang khusus bagi Anda yang mengutamakan privasi dan eksklusivitas. Dilengkapi dengan kursi elektrik independen yang dapat direbahkan hingga 140 derajat, layar sentuh AVOD dengan aneka film dan musik, serta atap kaca panoramik tembus pandang yang memberikan sudut pandang tak terbatas terhadap keindahan lanskap alam Nusantara.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6 border-t border-slate-100 text-sm">
                <div>
                    <h3 class="font-bold text-navy-900 mb-1">Kenyamanan Kursi</h3>
                    <p class="text-slate-500 text-xs">Kursi ergonomis 140° reclining dengan sandaran kaki otomatis dan meja lipat pribadi.</p>
                </div>
                <div>
                    <h3 class="font-bold text-navy-900 mb-1">Fasilitas Lengkap</h3>
                    <p class="text-slate-500 text-xs">Makanan dan minuman gratis, bantal dan selimut bersih, serta earphone pribadi.</p>
                </div>
                <div>
                    <h3 class="font-bold text-navy-900 mb-1">Layanan Khusus</h3>
                    <p class="text-slate-500 text-xs">Pramugara khusus yang siap melayani kebutuhan Anda sepanjang perjalanan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Class 2: Eksekutif New Generation -->
    <div class="bg-white rounded-2xl border border-slate-200 p-8 sm:p-10 card-60fps reveal-init delay-100">
        <div class="max-w-3xl">
            <span class="text-xs uppercase font-bold text-accent-600 tracking-wider">Favorit Perjalanan Bisnis</span>
            <h2 class="font-serif text-3xl font-bold text-navy-900 mt-1 mb-4">Eksekutif New Generation</h2>
            <p class="text-slate-600 leading-relaxed mb-6">
                Rangkaian gerbong stainless steel mutakhir dengan sistem peredam kebisingan kabin tingkat tinggi dan suspensi udara yang stabil. Menjamin waktu istirahat atau bekerja Anda tetap produktif tanpa terganggu guncangan rel.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6 border-t border-slate-100 text-sm">
                <div>
                    <h3 class="font-bold text-navy-900 mb-1">Formasi 2-2</h3>
                    <p class="text-slate-500 text-xs">Jarak antar kursi lega dengan ruang kaki luas dan pijakan kaki fleksibel.</p>
                </div>
                <div>
                    <h3 class="font-bold text-navy-900 mb-1">Daya & Konektivitas</h3>
                    <p class="text-slate-500 text-xs">Stopkontak ganda dan port USB charging di setiap kursi untuk gawai Anda.</p>
                </div>
                <div>
                    <h3 class="font-bold text-navy-900 mb-1">Toilet Higienis</h3>
                    <p class="text-slate-500 text-xs">Toilet duduk ramah lingkungan yang dipantau dan dibersihkan secara berkala.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Class 3: Bisnis & Ekonomi Premium -->
    <div class="bg-white rounded-2xl border border-slate-200 p-8 sm:p-10 card-60fps reveal-init delay-150">
        <div class="max-w-3xl">
            <span class="text-xs uppercase font-bold text-accent-600 tracking-wider">Kenyamanan Terjangkau</span>
            <h2 class="font-serif text-3xl font-bold text-navy-900 mt-1 mb-4">Bisnis & Ekonomi Premium</h2>
            <p class="text-slate-600 leading-relaxed mb-6">
                Standar baru perjalanan hemat dengan kursi individual searah laju kereta api. Tidak ada lagi kursi berhadap-hadapan yang sempit; setiap kursi memiliki ruang kaki lapang, penyejuk udara AC terdistribusi rata, dan rak bagasi atas yang aman.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-6 border-t border-slate-100 text-sm">
                <div>
                    <h3 class="font-bold text-navy-900 mb-1">Individual Reclining</h3>
                    <p class="text-slate-500 text-xs">Sudut kemiringan kursi dapat diatur untuk kenyamanan istirahat selama perjalanan.</p>
                </div>
                <div>
                    <h3 class="font-bold text-navy-900 mb-1">AC Sentral Dingin</h3>
                    <p class="text-slate-500 text-xs">Sirkulasi udara sejuk merata sepanjang gerbong.</p>
                </div>
                <div>
                    <h3 class="font-bold text-navy-900 mb-1">Tarif Ekonomis</h3>
                    <p class="text-slate-500 text-xs">Pilihan tepat untuk liburan keluarga, perjalanan pelajar, dan perjalanan harian.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dining on Train Section -->
    <div class="bg-slate-100 rounded-2xl p-8 sm:p-10 card-60fps reveal-init delay-200">
        <div class="max-w-3xl">
            <span class="text-xs uppercase font-bold text-accent-600 tracking-wider">Kereta Restorasi</span>
            <h2 class="font-serif text-3xl font-bold text-navy-900 mt-1 mb-4">Kuliner Khas Nusantara di Atas Rel</h2>
            <p class="text-slate-600 leading-relaxed mb-6">
                Nikmati kelezatan kuliner otentik Indonesia yang disajikan hangat di gerbong restorasi, mulai dari Nasi Goreng Parahyangan legendaris, Soto Ayam, hingga seduhan kopi lokal pilihan petani Nusantara.
            </p>
            <div class="flex items-center gap-4">
                <a href="{{ route('schedules') }}" class="px-6 py-3 rounded-xl bg-navy-900 text-white font-bold text-sm hover:bg-navy-800 transition btn-press">
                    Cek Jadwal Kereta &rarr;
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
