@extends('layouts.admin')
@section('title', 'Detail Pesanan #' . $booking->booking_number)
@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.bookings.index') }}" class="text-slate-500 hover:text-slate-700">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-slate-900">Detail Pesanan #{{ $booking->booking_number }}</h1>
    </div>
    
    <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST" class="flex gap-2 items-center">
        @csrf @method('PATCH')
        <label for="status" class="text-sm font-medium text-slate-700">Update Status:</label>
        <select name="status" id="status" class="rounded-md border-slate-300 shadow-sm text-sm focus:border-accent-500 focus:ring-accent-500 py-1">
            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Batal</option>
        </select>
        <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-3 py-1.5 rounded text-sm font-medium">Update</button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Informasi Perjalanan -->
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b">Informasi Perjalanan</h2>
            @if($booking->schedule)
            <div class="flex flex-col md:flex-row justify-between mb-4">
                <div>
                    <div class="text-sm text-slate-500 mb-1">Kereta</div>
                    <div class="font-medium">{{ $booking->schedule->train->name ?? '-' }} ({{ $booking->schedule->train->train_number ?? '-' }})</div>
                    <div class="text-sm text-slate-600 capitalize mt-1">{{ $booking->schedule->class_type ?? $booking->schedule->class }}</div>
                </div>
                <div class="mt-4 md:mt-0 text-right md:text-left">
                    <div class="text-sm text-slate-500 mb-1">Tanggal Keberangkatan</div>
                    <div class="font-medium">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('d M Y') }}</div>
                </div>
            </div>
            <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-lg">
                <div class="flex-1">
                    <div class="text-sm text-slate-500">Berangkat</div>
                    <div class="text-xl font-bold">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }}</div>
                    <div class="font-medium mt-1">{{ $booking->schedule->originStation->name ?? '-' }} ({{ $booking->schedule->originStation->code ?? '-' }})</div>
                </div>
                <div class="text-slate-400">&rarr;</div>
                <div class="flex-1 text-right">
                    <div class="text-sm text-slate-500">Tiba</div>
                    <div class="text-xl font-bold">{{ \Carbon\Carbon::parse($booking->schedule->arrival_time)->format('H:i') }}</div>
                    <div class="font-medium mt-1">{{ $booking->schedule->destinationStation->name ?? '-' }} ({{ $booking->schedule->destinationStation->code ?? '-' }})</div>
                </div>
            </div>
            @else
            <div class="text-slate-500 italic">Jadwal tidak ditemukan atau telah dihapus.</div>
            @endif
        </div>

        <!-- Daftar Penumpang -->
        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Daftar Penumpang</h2>
            </div>
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 font-medium">Nama</th>
                        <th class="px-6 py-3 font-medium">ID (KTP/Paspor)</th>
                        <th class="px-6 py-3 font-medium">Kursi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($booking->passengers ?? [] as $passenger)
                    <tr>
                        <td class="px-6 py-4">{{ $passenger->name }}</td>
                        <td class="px-6 py-4">{{ $passenger->id_number }}</td>
                        <td class="px-6 py-4 font-medium">{{ $passenger->seat->seat_number ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-4 text-center">Tidak ada data penumpang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Info Pemesan -->
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b">Pemesan</h2>
            <div class="space-y-3">
                <div>
                    <div class="text-sm text-slate-500">Nama</div>
                    <div class="font-medium">{{ $booking->user->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-sm text-slate-500">Email</div>
                    <div>{{ $booking->user->email ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-sm text-slate-500">Telepon</div>
                    <div>{{ $booking->user->phone ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Info Pembayaran -->
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b">Pembayaran</h2>
            @if($booking->payment)
            <div class="space-y-3">
                <div>
                    <div class="text-sm text-slate-500">Status</div>
                    @if($booking->payment->status == 'paid') <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 mt-1">Lunas</span>
                    @elseif($booking->payment->status == 'failed') <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800 mt-1">Gagal</span>
                    @else <span class="inline-flex rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800 mt-1">Menunggu</span> @endif
                </div>
                <div>
                    <div class="text-sm text-slate-500">Metode</div>
                    <div class="uppercase">{{ $booking->payment->payment_method ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-sm text-slate-500">Total Tagihan</div>
                    <div class="text-lg font-bold text-slate-900">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</div>
                </div>
                @if($booking->payment->paid_at)
                <div>
                    <div class="text-sm text-slate-500">Tanggal Bayar</div>
                    <div>{{ \Carbon\Carbon::parse($booking->payment->paid_at)->format('d M Y H:i') }}</div>
                </div>
                @endif
            </div>
            @else
            <div class="text-slate-500 italic mb-3">Belum ada data pembayaran.</div>
            <div>
                <div class="text-sm text-slate-500">Total Tagihan</div>
                <div class="text-lg font-bold text-slate-900">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
