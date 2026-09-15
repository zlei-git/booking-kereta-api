<?php
$base = 'D:/booking-kereta-api/resources/views/admin';

$dirs = ['stations', 'trains', 'schedules', 'bookings', 'payments', 'customers', 'contacts'];
foreach($dirs as $dir) {
    if(!is_dir("$base/$dir")) mkdir("$base/$dir", 0777, true);
}

$files = [];

// 1. Dashboard
$files['dashboard.blade.php'] = <<<'EOD'
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
                    <td class="px-6 py-4">{{ $booking->schedule->train->name }}</td>
                    <td class="px-6 py-4">{{ $booking->schedule->originStation->code }} &rarr; {{ $booking->schedule->destinationStation->code }}</td>
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
EOD;

// 2. stations/index
$files['stations/index.blade.php'] = <<<'EOD'
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
EOD;

// 3. stations/create
$files['stations/create.blade.php'] = <<<'EOD'
@extends('layouts.admin')
@section('title', 'Tambah Stasiun')
@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.stations.index') }}" class="text-slate-500 hover:text-slate-700">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-slate-900">Tambah Stasiun</h1>
</div>
<div class="bg-white rounded-lg border border-slate-200 p-6 max-w-2xl">
    <form action="{{ route('admin.stations.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Stasiun</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="code" class="block text-sm font-medium text-slate-700 mb-1">Kode Stasiun</label>
                <input type="text" name="code" id="code" value="{{ old('code') }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500 uppercase" maxlength="10" required>
                @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="city" class="block text-sm font-medium text-slate-700 mb-1">Kota</label>
                <input type="text" name="city" id="city" value="{{ old('city') }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="h-4 w-4 rounded border-slate-300 text-accent-600 focus:ring-accent-500" {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 block text-sm text-slate-700">Status Aktif</label>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-accent-600 hover:bg-accent-500 text-white px-4 py-2 rounded-md font-medium text-sm transition">Simpan</button>
            <a href="{{ route('admin.stations.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-md font-medium text-sm transition">Batal</a>
        </div>
    </form>
</div>
@endsection
EOD;

// 4. stations/edit
$files['stations/edit.blade.php'] = <<<'EOD'
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
EOD;

// 5. trains/index
$files['trains/index.blade.php'] = <<<'EOD'
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
EOD;

// 6. trains/create
$files['trains/create.blade.php'] = <<<'EOD'
@extends('layouts.admin')
@section('title', 'Tambah Kereta')
@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.trains.index') }}" class="text-slate-500 hover:text-slate-700">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-slate-900">Tambah Kereta</h1>
</div>
<div class="bg-white rounded-lg border border-slate-200 p-6 max-w-2xl">
    <form action="{{ route('admin.trains.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Kereta</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="train_number" class="block text-sm font-medium text-slate-700 mb-1">Nomor Kereta</label>
                <input type="text" name="train_number" id="train_number" value="{{ old('train_number') }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500 uppercase" required>
                @error('train_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Fasilitas</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach(['wifi' => 'WiFi', 'ac' => 'AC', 'power_outlet' => 'Stopkontak', 'meal_service' => 'Layanan Makan', 'luggage_storage' => 'Bagasi'] as $value => $label)
                    <div class="flex items-center">
                        <input type="checkbox" name="facilities[]" id="fac_{{ $value }}" value="{{ $value }}" class="h-4 w-4 rounded border-slate-300 text-accent-600 focus:ring-accent-500" {{ is_array(old('facilities')) && in_array($value, old('facilities')) ? 'checked' : '' }}>
                        <label for="fac_{{ $value }}" class="ml-2 block text-sm text-slate-700">{{ $label }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="h-4 w-4 rounded border-slate-300 text-accent-600 focus:ring-accent-500" {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 block text-sm text-slate-700">Status Aktif</label>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-accent-600 hover:bg-accent-500 text-white px-4 py-2 rounded-md font-medium text-sm transition">Simpan</button>
            <a href="{{ route('admin.trains.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-md font-medium text-sm transition">Batal</a>
        </div>
    </form>
</div>
@endsection
EOD;

// 7. trains/edit
$files['trains/edit.blade.php'] = <<<'EOD'
@extends('layouts.admin')
@section('title', 'Edit Kereta')
@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.trains.index') }}" class="text-slate-500 hover:text-slate-700">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-slate-900">Edit Kereta</h1>
</div>
<div class="bg-white rounded-lg border border-slate-200 p-6 max-w-2xl">
    <form action="{{ route('admin.trains.update', $train) }}" method="POST">
        @csrf @method('PUT')
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Kereta</label>
                <input type="text" name="name" id="name" value="{{ old('name', $train->name) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="train_number" class="block text-sm font-medium text-slate-700 mb-1">Nomor Kereta</label>
                <input type="text" name="train_number" id="train_number" value="{{ old('train_number', $train->train_number) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500 uppercase" required>
                @error('train_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="3" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500">{{ old('description', $train->description) }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Fasilitas</label>
                <div class="grid grid-cols-2 gap-2">
                    @php $facilities = old('facilities', $train->facilities ?? []); @endphp
                    @foreach(['wifi' => 'WiFi', 'ac' => 'AC', 'power_outlet' => 'Stopkontak', 'meal_service' => 'Layanan Makan', 'luggage_storage' => 'Bagasi'] as $value => $label)
                    <div class="flex items-center">
                        <input type="checkbox" name="facilities[]" id="fac_{{ $value }}" value="{{ $value }}" class="h-4 w-4 rounded border-slate-300 text-accent-600 focus:ring-accent-500" {{ in_array($value, is_array($facilities) ? $facilities : []) ? 'checked' : '' }}>
                        <label for="fac_{{ $value }}" class="ml-2 block text-sm text-slate-700">{{ $label }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="h-4 w-4 rounded border-slate-300 text-accent-600 focus:ring-accent-500" {{ old('is_active', $train->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 block text-sm text-slate-700">Status Aktif</label>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-accent-600 hover:bg-accent-500 text-white px-4 py-2 rounded-md font-medium text-sm transition">Simpan Perubahan</button>
            <a href="{{ route('admin.trains.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-md font-medium text-sm transition">Batal</a>
        </div>
    </form>
</div>
@endsection
EOD;

// 8. schedules/index
$files['schedules/index.blade.php'] = <<<'EOD'
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
EOD;

// 9. schedules/create
$files['schedules/create.blade.php'] = <<<'EOD'
@extends('layouts.admin')
@section('title', 'Tambah Jadwal')
@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.schedules.index') }}" class="text-slate-500 hover:text-slate-700">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-slate-900">Tambah Jadwal</h1>
</div>
<div class="bg-white rounded-lg border border-slate-200 p-6 max-w-2xl">
    <form action="{{ route('admin.schedules.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="train_id" class="block text-sm font-medium text-slate-700 mb-1">Kereta</label>
                <select name="train_id" id="train_id" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                    <option value="">Pilih Kereta</option>
                    @foreach($trains as $train)
                        <option value="{{ $train->id }}" {{ old('train_id') == $train->id ? 'selected' : '' }}>{{ $train->name }} ({{ $train->train_number }})</option>
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
                            <option value="{{ $station->id }}" {{ old('origin_station_id') == $station->id ? 'selected' : '' }}>{{ $station->name }} ({{ $station->code }})</option>
                        @endforeach
                    </select>
                    @error('origin_station_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="destination_station_id" class="block text-sm font-medium text-slate-700 mb-1">Stasiun Tujuan</label>
                    <select name="destination_station_id" id="destination_station_id" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                        <option value="">Pilih Stasiun Tujuan</option>
                        @foreach($stations as $station)
                            <option value="{{ $station->id }}" {{ old('destination_station_id') == $station->id ? 'selected' : '' }}>{{ $station->name }} ({{ $station->code }})</option>
                        @endforeach
                    </select>
                    @error('destination_station_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="departure_time" class="block text-sm font-medium text-slate-700 mb-1">Waktu Berangkat</label>
                    <input type="datetime-local" name="departure_time" id="departure_time" value="{{ old('departure_time') }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                    @error('departure_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="arrival_time" class="block text-sm font-medium text-slate-700 mb-1">Waktu Tiba</label>
                    <input type="datetime-local" name="arrival_time" id="arrival_time" value="{{ old('arrival_time') }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                    @error('arrival_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="class" class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
                    <select name="class" id="class" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                        <option value="">Pilih Kelas</option>
                        <option value="ekonomi" {{ old('class') == 'ekonomi' ? 'selected' : '' }}>Ekonomi</option>
                        <option value="bisnis" {{ old('class') == 'bisnis' ? 'selected' : '' }}>Bisnis</option>
                        <option value="eksekutif" {{ old('class') == 'eksekutif' ? 'selected' : '' }}>Eksekutif</option>
                    </select>
                    @error('class') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-slate-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" min="0" class="w-full rounded-md border-slate-300 shadow-sm focus:border-accent-500 focus:ring-accent-500" required>
                    @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="h-4 w-4 rounded border-slate-300 text-accent-600 focus:ring-accent-500" {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 block text-sm text-slate-700">Status Aktif</label>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-accent-600 hover:bg-accent-500 text-white px-4 py-2 rounded-md font-medium text-sm transition">Simpan</button>
            <a href="{{ route('admin.schedules.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-md font-medium text-sm transition">Batal</a>
        </div>
    </form>
</div>
@endsection
EOD;

// 10. schedules/edit
$files['schedules/edit.blade.php'] = <<<'EOD'
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
EOD;

// 11. bookings/index
$files['bookings/index.blade.php'] = <<<'EOD'
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
EOD;

// 12. bookings/show
$files['bookings/show.blade.php'] = <<<'EOD'
@extends('layouts.admin')
@section('title', 'Detail Pesanan #' . $booking->booking_number)
@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.bookings.index') }}" class="text-slate-500 hover:text-slate-700">&larr; Kembali</a>
        <h1 class="text-2xl font-bold text-slate-900">Detail Pesanan #{{ $booking->booking_number }}</h1>
    </div>
    
    <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST" class="flex gap-2 items-center">
        @csrf @method('PATCH')
        <label for="status" class="text-sm font-medium text-slate-700">Update Status:</label>
        <select name="status" id="status" class="rounded-md border-slate-300 shadow-sm text-sm focus:border-accent-500 focus:ring-accent-500 py-1">
            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Batal</option>
        </select>
        <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white px-3 py-1.5 rounded text-sm font-medium">Update</button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Informasi Perjalanan -->
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b">Informasi Perjalanan</h2>
            @if($booking->schedule)
            <div class="flex flex-col md:flex-row justify-between mb-4">
                <div>
                    <div class="text-sm text-slate-500 mb-1">Kereta</div>
                    <div class="font-medium">{{ $booking->schedule->train->name ?? '-' }} ({{ $booking->schedule->train->train_number ?? '-' }})</div>
                    <div class="text-sm text-slate-600 capitalize mt-1">{{ $booking->schedule->class }}</div>
                </div>
                <div class="mt-4 md:mt-0 text-right md:text-left">
                    <div class="text-sm text-slate-500 mb-1">Tanggal Keberangkatan</div>
                    <div class="font-medium">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('d M Y') }}</div>
                </div>
            </div>
            <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-lg">
                <div class="flex-1">
                    <div class="text-sm text-slate-500">Berangkat</div>
                    <div class="text-xl font-bold">{{ \Carbon\Carbon::parse($booking->schedule->departure_time)->format('H:i') }}</div>
                    <div class="font-medium mt-1">{{ $booking->schedule->originStation->name ?? '-' }} ({{ $booking->schedule->originStation->code ?? '-' }})</div>
                </div>
                <div class="text-slate-400">&rarr;</div>
                <div class="flex-1 text-right">
                    <div class="text-sm text-slate-500">Tiba</div>
                    <div class="text-xl font-bold">{{ \Carbon\Carbon::parse($booking->schedule->arrival_time)->format('H:i') }}</div>
                    <div class="font-medium mt-1">{{ $booking->schedule->destinationStation->name ?? '-' }} ({{ $booking->schedule->destinationStation->code ?? '-' }})</div>
                </div>
            </div>
            @else
            <div class="text-slate-500 italic">Jadwal tidak ditemukan atau telah dihapus.</div>
            @endif
        </div>

        <!-- Daftar Penumpang -->
        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Daftar Penumpang</h2>
            </div>
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 font-medium">Nama</th>
                        <th class="px-6 py-3 font-medium">ID (KTP/Paspor)</th>
                        <th class="px-6 py-3 font-medium">Kursi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($booking->passengers ?? [] as $passenger)
                    <tr>
                        <td class="px-6 py-4">{{ $passenger->name }}</td>
                        <td class="px-6 py-4">{{ $passenger->id_number }}</td>
                        <td class="px-6 py-4 font-medium">{{ $passenger->seat->seat_number ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-4 text-center">Tidak ada data penumpang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Info Pemesan -->
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b">Pemesan</h2>
            <div class="space-y-3">
                <div>
                    <div class="text-sm text-slate-500">Nama</div>
                    <div class="font-medium">{{ $booking->user->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-sm text-slate-500">Email</div>
                    <div>{{ $booking->user->email ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-sm text-slate-500">Telepon</div>
                    <div>{{ $booking->user->phone ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Info Pembayaran -->
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4 pb-2 border-b">Pembayaran</h2>
            @if($booking->payment)
            <div class="space-y-3">
                <div>
                    <div class="text-sm text-slate-500">Status</div>
                    @if($booking->payment->status == 'paid') <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800 mt-1">Lunas</span>
                    @elseif($booking->payment->status == 'failed') <span class="inline-flex rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800 mt-1">Gagal</span>
                    @else <span class="inline-flex rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800 mt-1">Menunggu</span> @endif
                </div>
                <div>
                    <div class="text-sm text-slate-500">Metode</div>
                    <div class="uppercase">{{ $booking->payment->payment_method ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-sm text-slate-500">Total Tagihan</div>
                    <div class="text-lg font-bold text-slate-900">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</div>
                </div>
                @if($booking->payment->paid_at)
                <div>
                    <div class="text-sm text-slate-500">Tanggal Bayar</div>
                    <div>{{ \Carbon\Carbon::parse($booking->payment->paid_at)->format('d M Y H:i') }}</div>
                </div>
                @endif
            </div>
            @else
            <div class="text-slate-500 italic mb-3">Belum ada data pembayaran.</div>
            <div>
                <div class="text-sm text-slate-500">Total Tagihan</div>
                <div class="text-lg font-bold text-slate-900">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
EOD;

// 13. payments/index
$files['payments/index.blade.php'] = <<<'EOD'
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
EOD;

// 14. customers/index
$files['customers/index.blade.php'] = <<<'EOD'
@extends('layouts.admin')
@section('title', 'Data Pelanggan')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Data Pelanggan</h1>
</div>
<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 font-medium">Nama</th>
                    <th class="px-6 py-3 font-medium">Email</th>
                    <th class="px-6 py-3 font-medium">Telepon</th>
                    <th class="px-6 py-3 font-medium">Total Pesanan</th>
                    <th class="px-6 py-3 font-medium">Terdaftar</th>
                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($customers as $customer)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $customer->name }}</td>
                    <td class="px-6 py-4">{{ $customer->email }}</td>
                    <td class="px-6 py-4">{{ $customer->phone ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $customer->bookings_count ?? 0 }} pesanan</td>
                    <td class="px-6 py-4">{{ $customer->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="text-accent-600 hover:text-accent-500 font-medium">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-4 text-center text-slate-500">Belum ada pelanggan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())
    <div class="p-4 border-t border-slate-200">{{ $customers->links() }}</div>
    @endif
</div>
@endsection
EOD;

// 15. customers/show
$files['customers/show.blade.php'] = <<<'EOD'
@extends('layouts.admin')
@section('title', 'Detail Pelanggan')
@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.customers.index') }}" class="text-slate-500 hover:text-slate-700">&larr; Kembali</a>
    <h1 class="text-2xl font-bold text-slate-900">Detail Pelanggan</h1>
</div>

<div class="bg-white rounded-lg border border-slate-200 p-6 mb-8 max-w-2xl">
    <div class="flex items-center gap-4 mb-6">
        <div class="w-16 h-16 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-xl font-bold">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900">{{ $user->name }}</h2>
            <div class="text-slate-500">Bergabung sejak {{ $user->created_at->format('d M Y') }}</div>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <div class="text-sm text-slate-500">Email</div>
            <div class="font-medium">{{ $user->email }}</div>
        </div>
        <div>
            <div class="text-sm text-slate-500">Telepon</div>
            <div class="font-medium">{{ $user->phone ?? '-' }}</div>
        </div>
        <div>
            <div class="text-sm text-slate-500">Total Pesanan</div>
            <div class="font-medium">{{ $user->bookings->count() }} pesanan</div>
        </div>
    </div>
</div>

<h2 class="text-lg font-bold text-slate-900 mb-4">Riwayat Pesanan</h2>
<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 font-medium">No. Pesanan</th>
                    <th class="px-6 py-3 font-medium">Tanggal</th>
                    <th class="px-6 py-3 font-medium">Kereta / Rute</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium">Total</th>
                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($user->bookings as $booking)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-medium text-slate-900">{{ $booking->booking_number }}</td>
                    <td class="px-6 py-4">{{ $booking->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        @if($booking->schedule)
                        {{ $booking->schedule->train->name ?? '-' }} <br>
                        <span class="text-xs text-slate-500">{{ $booking->schedule->originStation->code ?? '-' }} &rarr; {{ $booking->schedule->destinationStation->code ?? '-' }}</span>
                        @else
                        -
                        @endif
                    </td>
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
                <tr><td colspan="6" class="px-6 py-4 text-center text-slate-500">Belum ada riwayat pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
EOD;

// 16. contacts/index
$files['contacts/index.blade.php'] = <<<'EOD'
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
EOD;

// 17. contacts/show
$files['contacts/show.blade.php'] = <<<'EOD'
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
EOD;

foreach($files as $path => $content) {
    file_put_contents("$base/$path", $content);
    echo "Created $base/$path\n";
}
?>
