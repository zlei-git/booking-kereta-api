@extends('layouts.admin')
@section('title', 'Manajemen Pesanan')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Manajemen Pesanan</h1>
</div>

<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
    <div class="p-4 border-b border-slate-200 flex gap-2">
        @php $currentStatus = request('status', 'all'); @endphp
        <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 rounded-md text-sm font-medium {{ $currentStatus == 'all' ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50' }}">Semua</a>
        <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-md text-sm font-medium {{ $currentStatus == 'pending' ? 'bg-yellow-50 text-yellow-800' : 'text-slate-600 hover:bg-slate-50' }}">Menunggu</a>
        <a href="{{ route('admin.bookings.index', ['status' => 'confirmed']) }}" class="px-4 py-2 rounded-md text-sm font-medium {{ $currentStatus == 'confirmed' ? 'bg-blue-50 text-blue-800' : 'text-slate-600 hover:bg-slate-50' }}">Dikonfirmasi</a>
        <a href="{{ route('admin.bookings.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-md text-sm font-medium {{ $currentStatus == 'completed' ? 'bg-green-50 text-green-800' : 'text-slate-600 hover:bg-slate-50' }}">Selesai</a>
        <a href="{{ route('admin.bookings.index', ['status' => 'cancelled']) }}" class="px-4 py-2 rounded-md text-sm font-medium {{ $currentStatus == 'cancelled' ? 'bg-red-50 text-red-800' : 'text-slate-600 hover:bg-slate-50' }}">Batal</a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 font-medium">No. Pesanan</th>
                    <th class="px-6 py-3 font-medium">Pelanggan</th>
                    <th class="px-6 py-3 font-medium">Kereta</th>
                    <th class="px-6 py-3 font-medium">Tanggal</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium">Pembayaran</th>
                    <th class="px-6 py-3 font-medium">Total</th>
                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($bookings as $booking)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $booking->booking_number }}</td>
                    <td class="px-6 py-4">{{ $booking->user->name }}</td>
                    <td class="px-6 py-4">{{ $booking->schedule->train->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $booking->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        @if($booking->status == 'pending') <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Menunggu</span>
                        @elseif($booking->status == 'confirmed') <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">Dikonfirmasi</span>
                        @elseif($booking->status == 'completed') <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Selesai</span>
                        @else <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Batal</span> @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($booking->payment)
                            @if($booking->payment->status == 'paid') <span class="text-green-600 font-medium">Lunas</span>
                            @elseif($booking->payment->status == 'failed') <span class="text-red-600 font-medium">Gagal</span>
                            @else <span class="text-yellow-600 font-medium">Menunggu</span> @endif
                        @else
                            <span class="text-slate-400">Belum ada</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="text-accent-600 hover:text-accent-500 font-medium">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-4 text-center text-slate-500">Belum ada data pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
    <div class="p-4 border-t border-slate-200">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection
