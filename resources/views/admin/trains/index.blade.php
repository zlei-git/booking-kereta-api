@extends('layouts.admin')
@section('title', 'Manajemen Kereta')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Manajemen Kereta</h1>
    <a href="{{ route('admin.trains.create') }}" class="bg-accent-600 hover:bg-accent-500 text-white px-4 py-2 rounded-md font-medium text-sm transition">Tambah Kereta</a>
</div>
<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 font-medium">Nama</th>
                    <th class="px-6 py-3 font-medium">Nomor</th>
                    <th class="px-6 py-3 font-medium">Kelas</th>
                    <th class="px-6 py-3 font-medium">Fasilitas</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($trains as $train)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $train->name }}</td>
                    <td class="px-6 py-4">{{ $train->train_number }}</td>
                    <td class="px-6 py-4">
                        @foreach($train->classes ?? [] as $class)
                            <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-800 mr-1">{{ ucfirst($class) }}</span>
                        @endforeach
                    </td>
                    <td class="px-6 py-4">
                        @foreach($train->facilities ?? [] as $facility)
                            <span class="inline-flex rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 mr-1">{{ ucfirst(str_replace('_', ' ', $facility)) }}</span>
                        @endforeach
                    </td>
                    <td class="px-6 py-4">
                        @if($train->is_active) <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Aktif</span>
                        @else <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Nonaktif</span> @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <a href="{{ route('admin.trains.edit', $train) }}" class="text-accent-600 hover:text-accent-500 font-medium">Edit</a>
                        <form action="{{ route('admin.trains.destroy', $train) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kereta ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-500 font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-4 text-center text-slate-500">Belum ada kereta.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($trains->hasPages())
    <div class="p-4 border-t border-slate-200">{{ $trains->links() }}</div>
    @endif
</div>
@endsection
