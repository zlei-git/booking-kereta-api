@extends('layouts.admin')
@section('title', 'Edit Stasiun')
@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.stations.index') }}" class="text-slate-500 hover:text-slate-700">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-slate-900">Edit Stasiun</h1>
</div>
<div class="bg-white rounded-lg border border-slate-200 p-6 max-w-2xl">
    <form action="{{ route('admin.stations.update', $station) }}" method="POST">
        @csrf @method('PUT')
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Stasiun</label>
                <input type="text" name="name" id="name" value="{{ old('name', $station->name) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="code" class="block text-sm font-medium text-slate-700 mb-1">Kode Stasiun</label>
                <input type="text" name="code" id="code" value="{{ old('code', $station->code) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500 uppercase" maxlength="10" required>
                @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="city" class="block text-sm font-medium text-slate-700 mb-1">Kota</label>
                <input type="text" name="city" id="city" value="{{ old('city', $station->city) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="h-4 w-4 rounded border-slate-300 text-accent-600 focus:ring-accent-500" {{ old('is_active', $station->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 block text-sm text-slate-700">Status Aktif</label>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-accent-600 hover:bg-accent-500 text-white px-4 py-2 rounded-md font-medium text-sm transition">Simpan Perubahan</button>
            <a href="{{ route('admin.stations.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-md font-medium text-sm transition">Batal</a>
        </div>
    </form>
</div>
@endsection
