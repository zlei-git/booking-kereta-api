@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg p-5 border border-slate-200">
        <div class="text-sm text-slate-500 mb-1">Total Pesanan</div>
        <div class="text-2xl font-semibold text-slate-900">{{ $stats['total_bookings'] }}</div>
    </div>
    <div class="bg-white rounded-lg p-5 border border-slate-200">
        <div class="text-sm text-slate-500 mb-1">Pesanan Hari Ini</div>
        <div class="text-2xl font-semibold text-slate-900">{{ $stats['today_bookings'] }}</div>
    </div>
    <div class="bg-white rounded-lg p-5 border border-slate-200">
        <div class="text-sm text-slate-500 mb-1">Pendapatan</div>
        <div class="text-2xl font-semibold text-slate-900">Rp{{ number_format($stats['revenue'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-white rounded-lg p-5 border border-slate-200">
        <div class="text-sm text-slate-500 mb-1">Menunggu Pembayaran</div>
        <div class="text-2xl font-semibold text-slate-900">{{ $stats['pending_payments'] }}</div>
    </div>
    <div class="bg-white rounded-lg p-5 border border-slate-200">
        <div class="text-sm text-slate-500 mb-1">Kereta Aktif</div>
        <div class="text-2xl font-semibold text-slate-900">{{ $stats['active_trains'] }}</div>
    </div>
    <div class="bg-white rounded-lg p-5 border border-slate-200">
        <div class="text-sm text-slate-500 mb-1">Total Pelanggan</div>
        <div class="text-2xl font-semibold text-slate-900">{{ $stats['total_customers'] }}</div>
    </div>
</div>

<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h2 class="text-lg font-semibold text-slate-900">Pesanan Terbaru</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 font-medium">No. Pesanan</th>
                    <th class="px-6 py-3 font-medium">Pelanggan</th>
                    <th class="px-6 py-3 font-medium">Kereta</th>
                    <th class="px-6 py-3 font-medium">Rute</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium">Total</th>
                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($recentBookings as $booking)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4">{{ $booking->booking_number }}</td>
                    <td class="px-6 py-4">{{ $booking->user->name }}</td>
                    <td class="px-6 py-4">{{ $booking->schedule->train->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $booking->schedule->originStation->code ?? '-' }} &rarr; {{ $booking->schedule->destinationStation->code ?? '-' }}</td>
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
                <tr><td colspan="7" class="px-6 py-4 text-center text-slate-500">Belum ada pesanan terbaru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
