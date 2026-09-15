@extends('layouts.admin')
@section('title', 'Manajemen Jadwal')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Manajemen Jadwal</h1>
    <a href="{{ route('admin.schedules.create') }}" class="bg-accent-600 hover:bg-accent-500 text-white px-4 py-2 rounded-md font-medium text-sm transition">Tambah Jadwal</a>
</div>
<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 font-medium">Kereta</th>
                    <th class="px-6 py-3 font-medium">Rute</th>
                    <th class="px-6 py-3 font-medium">Berangkat - Tiba</th>
                    <th class="px-6 py-3 font-medium">Kelas</th>
                    <th class="px-6 py-3 font-medium">Harga</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($schedules as $schedule)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $schedule->train->name }}</td>
                    <td class="px-6 py-4">{{ $schedule->originStation->code }} &rarr; {{ $schedule->destinationStation->code }}</td>
                    <td class="px-6 py-4">
                        <div>{{ \Carbon\Carbon::parse($schedule->departure_time)->format('d M Y') }}</div>
                        <div class="text-slate-500">{{ \Carbon\Carbon::parse($schedule->departure_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->arrival_time)->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 uppercase text-xs">{{ $schedule->class }}</td>
                    <td class="px-6 py-4">Rp{{ number_format($schedule->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($schedule->is_active) <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Aktif</span>
                        @else <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Nonaktif</span> @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <a href="{{ route('admin.schedules.edit', $schedule) }}" class="text-accent-600 hover:text-accent-500 font-medium">Edit</a>
                        <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-500 font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-4 text-center text-slate-500">Belum ada jadwal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($schedules->hasPages())
    <div class="p-4 border-t border-slate-200">{{ $schedules->links() }}</div>
    @endif
</div>
@endsection
