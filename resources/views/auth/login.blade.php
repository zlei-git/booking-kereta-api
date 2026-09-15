@extends('layouts.app')

@section('title', 'Masuk ke Akun Anda')

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-xl shadow-sm border border-slate-200 p-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-slate-900">Masuk ke Akun Anda</h2>
            <p class="mt-2 text-sm text-slate-500">Silakan masuk untuk melanjutkan pemesanan</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

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
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-accent-500 focus:border-accent-500 sm:text-sm">
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="h-4 w-4 text-accent-600 focus:ring-accent-500 border-slate-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-slate-700">
                        Ingat saya
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" class="font-medium text-accent-600 hover:text-accent-500">
                            Lupa password?
                        </a>
                    </div>
                @endif
            </div>

            <div>
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent-600 hover:bg-accent-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-500 transition-colors">
                    Masuk
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm">
            <span class="text-slate-500">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="font-medium text-accent-600 hover:text-accent-500 ml-1">
                Daftar sekarang
            </a>
        </div>
    </div>
</div>
@endsection
