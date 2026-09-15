@extends('layouts.app')
@section('title', 'Hubungi Kami')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="text-center mb-10 animate-fade-in-up">
        <h1 class="text-3xl font-bold text-navy-900">Hubungi Kami</h1>
        <p class="mt-2 text-slate-600">Punya pertanyaan atau kendala? Tim kami siap membantu Anda.</p>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-green-50 border border-green-200 rounded-md text-green-700 flex items-center animate-fade-in-down">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden card-60fps reveal-init">
        
        <!-- Contact Info (Left) -->
        <div class="w-full lg:w-1/3 bg-navy-900 text-white p-8">
            <h3 class="text-xl font-bold mb-6">Informasi Kontak</h3>
            
            <div class="space-y-6">
                <div>
                    <p class="font-medium text-xs text-slate-300 mb-1">Telepon (Layanan Pelanggan)</p>
                    <p class="text-xl font-bold text-white">(021) 121 / 121</p>
                </div>
                
                <div>
                    <p class="font-medium text-xs text-slate-300 mb-1">Email Resmi</p>
                    <p class="text-base text-white font-medium">layanan@nordicrail.id</p>
                </div>

                <div>
                    <p class="font-medium text-xs text-slate-300 mb-1">Jam Operasional</p>
                    <p class="text-sm text-white">Senin - Minggu (24 Jam Setiap Hari)</p>
                </div>
                
                <div>
                    <p class="font-medium text-xs text-slate-300 mb-1">Alamat Kantor</p>
                    <p class="text-sm text-slate-300">Gedung Presisi Stasiun Gambir Lt. 3, Jakarta Pusat</p>
                </div>
            </div>
        </div>

        <!-- Contact Form (Right) -->
        <div class="w-full lg:w-2/3 p-8">
            <h3 class="text-xl font-bold text-slate-900 mb-6">Kirim Pesan</h3>
            
            <form action="{{ route('contact.store') ?? '#' }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full border-slate-300 rounded-md shadow-sm focus:border-accent-500 focus:ring focus:ring-accent-500 focus:ring-opacity-50 @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full border-slate-300 rounded-md shadow-sm focus:border-accent-500 focus:ring focus:ring-accent-500 focus:ring-opacity-50 @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="subject" class="block text-sm font-medium text-slate-700 mb-1">Subjek</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required class="w-full border-slate-300 rounded-md shadow-sm focus:border-accent-500 focus:ring focus:ring-accent-500 focus:ring-opacity-50 @error('subject') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="message" class="block text-sm font-medium text-slate-700 mb-1">Pesan</label>
                    <textarea name="message" id="message" rows="5" required class="w-full border-slate-300 rounded-md shadow-sm focus:border-accent-500 focus:ring focus:ring-accent-500 focus:ring-opacity-50 @error('message') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="inline-flex justify-center items-center px-6 py-3 bg-accent-600 border border-transparent rounded-md font-semibold text-white hover:bg-accent-500 focus:outline-none focus:border-accent-700 focus:ring focus:ring-accent-200 active:bg-accent-600 transition shadow-sm btn-press">
                        Kirim Pesan
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
