@extends('layouts.admin')
@section('title', 'Detail Pelanggan')
@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.customers.index') }}" class="text-slate-500 hover:text-slate-700">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-slate-900">Detail Pelanggan</h1>
</div>

<div class="bg-white rounded-lg border border-slate-200 p-6 mb-8 max-w-2xl">
    <div class="flex items-center gap-4 mb-6">
        <div class="w-16 h-16 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xl font-bold">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900">{{ $user->name }}</h2>
            <div class="text-slate-500">Bergabung sejak {{ $user->created_at->format('d M Y') }}</div>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <div class="text-sm text-slate-500">Email</div>
            <div class="font-medium">{{ $user->email }}</div>
        </div>
        <div>
            <div class="text-sm text-slate-500">Telepon</div>
            <div class="font-medium">{{ $user->phone ?? '-' }}</div>
        </div>
        <div>
            <div class="text-sm text-slate-500">Total Pesanan</div>
            <div class="font-medium">{{ $user->bookings->count() }} pesanan</div>
        </div>
    </div>
</div>

<h2 class="text-lg font-bold text-slate-900 mb-4">Riwayat Pesanan</h2>
<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 font-medium">No. Pesanan</th>
                    <th class="px-6 py-3 font-medium">Tanggal</th>
                    <th class="px-6 py-3 font-medium">Kereta / Rute</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium">Total</th>
                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($user->bookings as $booking)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $booking->booking_number }}</td>
                    <td class="px-6 py-4">{{ $booking->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        @if($booking->schedule)
                        {{ $booking->schedule->train->name ?? '-' }} <br>
                        <span class="text-xs text-slate-500">{{ $booking->schedule->originStation->code ?? '-' }} &rarr; {{ $booking->schedule->destinationStation->code ?? '-' }}</span>
                        @else
                        -
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($booking->status == 'pending') <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Menunggu</span>
                        @elseif($booking->status == 'confirmed') <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">Dikonfirmasi</span>
                        @elseif($booking->status == 'completed') <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Selesai</span>
                        @else <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Batal</span> @endif
                    </td>
                    <td class="px-6 py-4">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="text-accent-600 hover:text-accent-500 font-medium">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-4 text-center text-slate-500">Belum ada riwayat pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
