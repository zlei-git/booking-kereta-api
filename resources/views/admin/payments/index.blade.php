@extends('layouts.admin')
@section('title', 'Manajemen Pembayaran')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Manajemen Pembayaran</h1>
</div>
<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 font-medium">Ref ID</th>
                    <th class="px-6 py-3 font-medium">No. Pesanan</th>
                    <th class="px-6 py-3 font-medium">Pelanggan</th>
                    <th class="px-6 py-3 font-medium">Metode</th>
                    <th class="px-6 py-3 font-medium">Jumlah</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($payments as $payment)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-xs font-mono">{{ $payment->payment_reference ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.bookings.show', $payment->booking) }}" class="text-accent-600 hover:underline">{{ $payment->booking->booking_number ?? '-' }}</a>
                    </td>
                    <td class="px-6 py-4">{{ $payment->booking->user->name ?? '-' }}</td>
                    <td class="px-6 py-4 uppercase">{{ $payment->payment_method }}</td>
                    <td class="px-6 py-4">Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($payment->status == 'paid') <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Lunas</span>
                        @elseif($payment->status == 'failed') <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Gagal</span>
                        @else <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Menunggu</span> @endif
                    </td>
                    <td class="px-6 py-4">{{ $payment->created_at->format('d M Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-4 text-center text-slate-500">Belum ada pembayaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="p-4 border-t border-slate-200">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
