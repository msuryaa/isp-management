@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

{{-- Back + Header --}}
<div class="mb-7">
    <a href="{{ route('users.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 font-medium transition mb-3 group">
        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Kembali ke Daftar
    </a>
    <h1 class="font-display text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Edit Data User</h1>
    <p class="text-sm text-gray-500 mt-1">Perbarui informasi pengguna <span class="font-semibold text-gray-700">{{ $user->name }}</span>.</p>
</div>

<div class="w-full">
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

        {{-- User info banner --}}
        <div class="px-6 sm:px-8 pt-6 pb-0">
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                </div>
                @if($user->role === 'administrator')
                    <span class="shrink-0 px-2.5 py-1 text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100 rounded-lg">Admin</span>
                @else
                    <span class="shrink-0 px-2.5 py-1 text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100 rounded-lg">Staff</span>
                @endif
            </div>
        </div>

        <div class="p-6 sm:p-8">
            <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nama Lengkap
                        <span class="text-rose-500 ml-0.5">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition @error('name') border-rose-400 @enderror"
                           required>
                    @error('name')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Alamat Email
                        <span class="text-rose-500 ml-0.5">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition @error('email') border-rose-400 @enderror"
                           required>
                    @error('email')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Password Baru
                        <span class="text-xs text-gray-400 font-normal ml-1">— kosongkan jika tidak diubah</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="passwordInput" name="password"
                               placeholder="Isi hanya jika ingin mengubah password"
                               class="w-full px-4 py-2.5 pr-11 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition @error('password') border-rose-400 @enderror">
                        <button type="button" onclick="togglePassword('passwordInput', this)"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Hak Akses (Role)
                        <span class="text-rose-500 ml-0.5">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="staff" class="peer sr-only"
                                   {{ old('role', $user->role) == 'staff' ? 'checked' : '' }}>
                            <div class="flex items-center gap-2.5 px-4 py-3 border-2 rounded-xl border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Staff</p>
                                    <p class="text-xs text-gray-400">Akses terbatas</p>
                                </div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="role" value="administrator" class="peer sr-only"
                                   {{ old('role', $user->role) == 'administrator' ? 'checked' : '' }}>
                            <div class="flex items-center gap-2.5 px-4 py-3 border-2 rounded-xl border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Administrator</p>
                                    <p class="text-xs text-gray-400">Akses penuh</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-2"></div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition shadow-sm shadow-blue-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Perbarui User
                    </button>
                    <a href="{{ route('users.index') }}"
                       class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-100 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

@endsection