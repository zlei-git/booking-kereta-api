@extends('layouts.admin')
@section('title', 'Edit Jadwal')
@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.schedules.index') }}" class="text-slate-500 hover:text-slate-700">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-slate-900">Edit Jadwal</h1>
</div>
<div class="bg-white rounded-lg border border-slate-200 p-6 max-w-2xl">
    <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
        @csrf @method('PUT')
        <div class="space-y-4">
            <div>
                <label for="train_id" class="block text-sm font-medium text-slate-700 mb-1">Kereta</label>
                <select name="train_id" id="train_id" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                    <option value="">Pilih Kereta</option>
                    @foreach($trains as $train)
                        <option value="{{ $train->id }}" {{ old('train_id', $schedule->train_id) == $train->id ? 'selected' : '' }}>{{ $train->name }} ({{ $train->train_number }})</option>
                    @endforeach
                </select>
                @error('train_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="origin_station_id" class="block text-sm font-medium text-slate-700 mb-1">Stasiun Asal</label>
                    <select name="origin_station_id" id="origin_station_id" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                        <option value="">Pilih Stasiun Asal</option>
                        @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ old('origin_station_id', $schedule->origin_station_id) == $station->id ? 'selected' : '' }}>{{ $station->name }} ({{ $station->code }})</option>
                        @endforeach
                    </select>
                    @error('origin_station_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="destination_station_id" class="block text-sm font-medium text-slate-700 mb-1">Stasiun Tujuan</label>
                    <select name="destination_station_id" id="destination_station_id" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                        <option value="">Pilih Stasiun Tujuan</option>
                        @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ old('destination_station_id', $schedule->destination_station_id) == $station->id ? 'selected' : '' }}>{{ $station->name }} ({{ $station->code }})</option>
                        @endforeach
                    </select>
                    @error('destination_station_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="departure_time" class="block text-sm font-medium text-slate-700 mb-1">Waktu Berangkat</label>
                    <input type="datetime-local" name="departure_time" id="departure_time" value="{{ old('departure_time', \Carbon\Carbon::parse($schedule->departure_time)->format('Y-m-d\TH:i')) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                    @error('departure_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="arrival_time" class="block text-sm font-medium text-slate-700 mb-1">Waktu Tiba</label>
                    <input type="datetime-local" name="arrival_time" id="arrival_time" value="{{ old('arrival_time', \Carbon\Carbon::parse($schedule->arrival_time)->format('Y-m-d\TH:i')) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                    @error('arrival_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="class" class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
                    <select name="class" id="class" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                        <option value="ekonomi" {{ old('class', $schedule->class) == 'ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                        <option value="bisnis" {{ old('class', $schedule->class) == 'bisnis' ? 'selected' : '' }}>Bisnis</option>
                        <option value="eksekutif" {{ old('class', $schedule->class) == 'eksekutif' ? 'selected' : '' }}>Eksekutif</option>
                    </select>
                    @error('class') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-slate-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $schedule->price) }}" min="0" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                    @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="h-4 w-4 rounded border-slate-300 text-accent-600 focus:ring-accent-500" {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 block text-sm text-slate-700">Status Aktif</label>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-accent-600 hover:bg-accent-500 text-white px-4 py-2 rounded-md font-medium text-sm transition">Simpan Perubahan</button>
            <a href="{{ route('admin.schedules.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-md font-medium text-sm transition">Batal</a>
        </div>
    </form>
</div>
@endsection
