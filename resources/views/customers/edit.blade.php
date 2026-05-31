@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')

{{-- Back + Header --}}
<div class="mb-7">
    <a href="{{ route('customers.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 font-medium transition mb-3 group">
        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Kembali ke Daftar
    </a>
    <h1 class="font-display text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Edit Data Pelanggan</h1>
    <p class="text-sm text-gray-500 mt-1">Perbarui informasi pelanggan <span class="font-semibold text-gray-700">{{ $customer->name }}</span>.</p>
</div>

<div class="w-full">
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        {{-- Customer info banner --}}
        <div class="px-6 sm:px-8 pt-6 pb-0">
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold shrink-0">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-gray-900 truncate">{{ $customer->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $customer->phone }}</p>
                </div>
                @php $statusStr = trim(strtolower($customer->status)); @endphp
                @if($statusStr === 'aktif')
                    <span class="shrink-0 px-2.5 py-1 text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg">Aktif</span>
                @elseif($statusStr === 'suspend')
                    <span class="shrink-0 px-2.5 py-1 text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100 rounded-lg">Suspend</span>
                @else
                    <span class="shrink-0 px-2.5 py-1 text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100 rounded-lg">Putus</span>
                @endif
            </div>
        </div>

        <div class="p-6 sm:p-8">
            <form action="{{ route('customers.update', $customer->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Grid Nama & No Telepon --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama Pelanggan
                            <span class="text-rose-500 ml-0.5">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $customer->name) }}"
                               class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition @error('name') border-rose-400 bg-rose-50 @else border-gray-200 @enderror"
                               required>
                        @error('name')
                            <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nomor Telepon
                            <span class="text-rose-500 ml-0.5">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                               class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition @error('phone') border-rose-400 bg-rose-50 @else border-gray-200 @enderror"
                               required>
                        @error('phone')
                            <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Alamat Lengkap
                        <span class="text-rose-500 ml-0.5">*</span>
                    </label>
                    <textarea name="address" rows="3"
                              class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition @error('address') border-rose-400 bg-rose-50 @else border-gray-200 @enderror"
                              required>{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Grid Paket & Harga --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Paket Internet
                            <span class="text-rose-500 ml-0.5">*</span>
                        </label>
                        <input type="text" name="internet_package" value="{{ old('internet_package', $customer->internet_package) }}"
                               class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition @error('internet_package') border-rose-400 bg-rose-50 @else border-gray-200 @enderror"
                               required>
                        @error('internet_package')
                            <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Harga Paket (IDR)
                            <span class="text-rose-500 ml-0.5">*</span>
                        </label>
                        <input type="number" name="package_price" value="{{ old('package_price', $customer->package_price) }}"
                               class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition @error('package_price') border-rose-400 bg-rose-50 @else border-gray-200 @enderror"
                               required>
                        @error('package_price')
                            <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Status Pelanggan --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Status Pelanggan
                        <span class="text-rose-500 ml-0.5">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        {{-- Aktif --}}
                        <label class="relative cursor-pointer">
                            <input type="radio" name="status" value="aktif" class="peer sr-only"
                                   {{ old('status', $customer->status) == 'aktif' ? 'checked' : '' }}>
                            <div class="flex flex-col items-center gap-1.5 px-3 py-3 border-2 rounded-xl border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition text-center">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-800 peer-checked:text-emerald-700">Aktif</p>
                            </div>
                        </label>
                        {{-- Suspend --}}
                        <label class="relative cursor-pointer">
                            <input type="radio" name="status" value="suspend" class="peer sr-only"
                                   {{ old('status', $customer->status) == 'suspend' ? 'checked' : '' }}>
                            <div class="flex flex-col items-center gap-1.5 px-3 py-3 border-2 rounded-xl border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 transition text-center">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9v6m-4.5 0V9M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-800 peer-checked:text-amber-700">Suspend</p>
                            </div>
                        </label>
                        {{-- Putus --}}
                        <label class="relative cursor-pointer">
                            <input type="radio" name="status" value="putus" class="peer sr-only"
                                   {{ old('status', $customer->status) == 'putus' ? 'checked' : '' }}>
                            <div class="flex flex-col items-center gap-1.5 px-3 py-3 border-2 rounded-xl border-gray-200 peer-checked:border-rose-500 peer-checked:bg-rose-50 transition text-center">
                                <div class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-800 peer-checked:text-rose-700">Putus</p>
                            </div>
                        </label>
                    </div>
                    @error('status')
                        <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Divider --}}
                <div class="border-t border-gray-100 pt-2"></div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition shadow-sm shadow-blue-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Perbarui Pelanggan
                    </button>
                    <a href="{{ route('customers.index') }}"
                       class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-100 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection