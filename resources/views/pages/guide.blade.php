@extends('layouts.app')

@section('title', 'Panduan Perjalanan — NordicRail')

@section('content')
<div class="bg-navy-950 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-3xl sm:text-5xl font-bold tracking-tight text-white">Panduan Perjalanan Kereta Api</h1>
        <p class="text-slate-300 text-base sm:text-lg font-light mt-3 max-w-2xl leading-relaxed">
            Informasi lengkap mengenai persyaratan dokumen, aturan bagasi, pembatalan tiket, dan hak reduksi penumpang demi kelancaran perjalanan Anda.
        </p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-12">
    
    <!-- Guide 1: Bagasi -->
    <div class="bg-white rounded-2xl border border-slate-200 p-8 sm:p-10">
        <span class="text-xs uppercase font-bold text-accent-600 tracking-wider">Bagasi & Barang Bawaan</span>
        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-navy-900 mt-1 mb-4">Ketentuan Berat dan Dimensi Bagasi</h2>
        <p class="text-slate-600 leading-relaxed mb-4">
            Setiap penumpang berhak membawa barang bawaan bebas biaya bagasi dengan ketentuan:
        </p>
        <ul class="space-y-3 text-sm text-slate-600 list-disc pl-5 leading-relaxed">
            <li>Berat maksimum <strong>20 kg</strong> per penumpang dengan volume maksimal 100 dm³ (dimensi maksimal 70 cm x 48 cm x 30 cm).</li>
            <li>Barang bawaan harus ditempatkan pada rak bagasi di atas tempat duduk atau pada tempat lain yang tidak mengganggu kenyamanan maupun keselamatan penumpang lain.</li>
            <li>Sepeda lipat diperkenankan masuk ke dalam kabin gerbong dengan berat maksimal 20 kg dan ukuran roda maksimal 22 inci.</li>
            <li>Barang yang dilarang meliputi binatang peliharaan, senjata tajam/api, zat kimia berbahaya atau mudah terbakar, serta makanan/minuman berbau menyengat.</li>
        </ul>
    </div>

    <!-- Guide 2: Boarding -->
    <div class="bg-white rounded-2xl border border-slate-200 p-8 sm:p-10">
        <span class="text-xs uppercase font-bold text-accent-600 tracking-wider">Pemeriksaan Stasiun</span>
        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-navy-900 mt-1 mb-4">Persyaratan Dokumen Boarding</h2>
        <p class="text-slate-600 leading-relaxed mb-4">
            Sebelum memasuki peron keberangkatan, penumpang wajib menunjukkan bukti pemesanan dan kartu identitas asli:
        </p>
        <ul class="space-y-3 text-sm text-slate-600 list-disc pl-5 leading-relaxed">
            <li>Nama pada tiket harus sesuai persis dengan kartu identitas asli yang sah (KTP, SIM, atau Paspor untuk WNA).</li>
            <li>Penumpang anak di bawah 17 tahun dapat menggunakan Kartu Identitas Anak (KIA) atau fotokopi Kartu Keluarga (KK).</li>
            <li>E-tiket digital pada ponsel dapat langsung dipindai di pintu boarding tanpa perlu mencetak kertas fisik.</li>
            <li>Penumpang disarankan telah tiba di stasiun sekurang-kurangnya 30 menit sebelum jadwal keberangkatan kereta.</li>
        </ul>
    </div>

    <!-- Guide 3: Pembatalan -->
    <div class="bg-white rounded-2xl border border-slate-200 p-8 sm:p-10">
        <span class="text-xs uppercase font-bold text-accent-600 tracking-wider">Perubahan Rencana</span>
        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-navy-900 mt-1 mb-4">Ketentuan Pembatalan & Perubahan Jadwal (Reschedule)</h2>
        <p class="text-slate-600 leading-relaxed mb-4">
            Apabila terjadi perubahan jadwal atau pembatalan perjalanan, berlaku ketentuan berikut:
        </p>
        <ul class="space-y-3 text-sm text-slate-600 list-disc pl-5 leading-relaxed">
            <li>Permohonan pembatalan dapat diajukan selambat-lambatnya 2 jam sebelum jam keberangkatan kereta api.</li>
            <li>Pengembalian bea pembatalan diberikan sebesar 75% dari harga tiket di luar bea pemesanan/layanan.</li>
            <li>Dana pengembalian ditransfer ke rekening bank pemesan dalam waktu maksimal 14 hari kerja.</li>
            <li>Perubahan jadwal (reschedule) dikenakan biaya administrasi 25% ditambah selisih tarif jika berpindah ke kelas atau tanggal dengan harga lebih tinggi.</li>
        </ul>
    </div>

    <!-- Guide 4: Reduksi -->
    <div class="bg-white rounded-2xl border border-slate-200 p-8 sm:p-10">
        <span class="text-xs uppercase font-bold text-accent-600 tracking-wider">Fasilitas Khusus</span>
        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-navy-900 mt-1 mb-4">Hak Reduksi & Diskon Penumpang</h2>
        <p class="text-slate-600 leading-relaxed mb-4">
            NusaRail memberikan fasilitas tarif khusus dan reduksi bagi kategori penumpang tertentu:
        </p>
        <ul class="space-y-3 text-sm text-slate-600 list-disc pl-5 leading-relaxed">
            <li><strong>Lansia (usia 60 tahun ke atas):</strong> Potongan harga 20% untuk semua kelas kereta antarkota.</li>
            <li><strong>Legiun Veteran RI:</strong> Diskon 50% pada hari kerja (Senin–Jumat) dan 30% pada akhir pekan/libur nasional.</li>
            <li><strong>Anggota TNI / POLRI:</strong> Diskon 25% untuk kelas Eksekutif dan 50% untuk kelas Bisnis/Ekonomi.</li>
            <li><strong>Penumpang Bayi (0–3 tahun):</strong> Gratis (Rp 0) dengan ketentuan tidak mengambil tempat duduk sendiri.</li>
        </ul>
    </div>

</div>
@endsection
