@extends('layouts.admin')
@section('title', 'Baca Pesan')
@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.contacts.index') }}" class="text-slate-500 hover:text-slate-700">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-slate-900">Pesan dari {{ $contact->name }}</h1>
</div>

<div class="bg-white rounded-lg border border-slate-200 p-6 max-w-3xl">
    <div class="flex justify-between items-start mb-6 pb-6 border-b border-slate-100">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 mb-1">{{ $contact->subject }}</h2>
            <div class="text-sm text-slate-500">
                Dari: <span class="font-medium text-slate-700">{{ $contact->name }}</span> ({{ $contact->email }})
            </div>
        </div>
        <div class="text-right">
            <div class="text-sm text-slate-500">{{ $contact->created_at->format('d M Y') }}</div>
            <div class="text-sm text-slate-500">{{ $contact->created_at->format('H:i') }} WIB</div>
        </div>
    </div>
    
    <div class="prose prose-slate max-w-none mb-8">
        {!! nl2br(e($contact->message)) !!}
    </div>

    @if(!$contact->is_read)
    <div class="pt-6 border-t border-slate-100">
        <form action="{{ route('admin.contacts.markAsRead', $contact) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit" class="bg-accent-600 hover:bg-accent-500 text-white px-4 py-2 rounded-md font-medium text-sm transition">
                Tandai Sudah Dibaca
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
