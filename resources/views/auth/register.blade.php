@extends('layouts.app')

@section('title', 'Buat Akun Baru')

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-xl shadow-sm border border-slate-200 p-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-slate-900">Buat Akun Baru</h2>
            <p class="mt-2 text-sm text-slate-500">Daftar untuk mulai memesan tiket kereta</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                <div class="mt-1">
                    <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}"
                        class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-accent-500 focus:border-accent-500 sm:text-sm">
                </div>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                        class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-accent-500 focus:border-accent-500 sm:text-sm">
                </div>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700">No. Telepon</label>
                <div class="mt-1">
                    <input id="phone" name="phone" type="text" autocomplete="tel" required value="{{ old('phone') }}"
                        class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-accent-500 focus:border-accent-500 sm:text-sm">
                </div>
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                        class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-accent-500 focus:border-accent-500 sm:text-sm">
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Konfirmasi Password</label>
                <div class="mt-1">
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                        class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-accent-500 focus:border-accent-500 sm:text-sm">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-600 hover:bg-accent-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition-colors">
                    Daftar
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm">
            <span class="text-slate-500">Sudah punya akun?</span>
            <a href="{{ route('login') }}" class="font-medium text-accent-600 hover:text-accent-500 ml-1">
                Masuk ke akun Anda
            </a>
        </div>
    </div>
</div>
@endsection
