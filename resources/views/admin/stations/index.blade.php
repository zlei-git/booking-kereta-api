@extends('layouts.admin')
@section('title', 'Manajemen Stasiun')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Manajemen Stasiun</h1>
    <a href="{{ route('admin.stations.create') }}" class="bg-accent-600 hover:bg-accent-500 text-white px-4 py-2 rounded-md font-medium text-sm transition">Tambah Stasiun</a>
</div>
<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 font-medium">Nama</th>
                    <th class="px-6 py-3 font-medium">Kode</th>
                    <th class="px-6 py-3 font-medium">Kota</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($stations as $station)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $station->name }}</td>
                    <td class="px-6 py-4">{{ $station->code }}</td>
                    <td class="px-6 py-4">{{ $station->city }}</td>
                    <td class="px-6 py-4">
                        @if($station->is_active) <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Aktif</span>
                        @else <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Nonaktif</span> @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <a href="{{ route('admin.stations.edit', $station) }}" class="text-accent-600 hover:text-accent-500 font-medium">Edit</a>
                        <form action="{{ route('admin.stations.destroy', $station) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus stasiun ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-500 font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-4 text-center text-slate-500">Belum ada stasiun.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stations->hasPages())
    <div class="p-4 border-t border-slate-200">{{ $stations->links() }}</div>
    @endif
</div>
@endsection
