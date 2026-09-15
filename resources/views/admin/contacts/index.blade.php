@extends('layouts.admin')
@section('title', 'Pesan Kontak')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Pesan Kontak</h1>
</div>
<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium">Nama</th>
                    <th class="px-6 py-3 font-medium">Email</th>
                    <th class="px-6 py-3 font-medium">Subjek</th>
                    <th class="px-6 py-3 font-medium">Tanggal</th>
                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($messages as $msg)
                <tr class="hover:bg-slate-50 {{ !$msg->is_read ? 'bg-blue-50/30 font-medium text-slate-900' : '' }}">
                    <td class="px-6 py-4">
                        @if($msg->is_read) <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">Dibaca</span>
                        @else <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">Baru</span> @endif
                    </td>
                    <td class="px-6 py-4">{{ $msg->name }}</td>
                    <td class="px-6 py-4">{{ $msg->email }}</td>
                    <td class="px-6 py-4">{{ Str::limit($msg->subject, 30) }}</td>
                    <td class="px-6 py-4">{{ $msg->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.contacts.show', $msg) }}" class="text-accent-600 hover:text-accent-500 font-medium">Baca</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-4 text-center text-slate-500">Belum ada pesan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($messages->hasPages())
    <div class="p-4 border-t border-slate-200">{{ $messages->links() }}</div>
    @endif
</div>
@endsection
