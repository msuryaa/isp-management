@extends('layouts.app')

@section('title', 'Kelola Pelanggan')

@section('content')

{{-- Page header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Pelanggan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola status langganan aktif, penangguhan (suspend), dan putusan layanan internet pelanggan.</p>
    </div>
    <a href="{{ route('customers.create') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition shadow-sm shadow-blue-200 self-start sm:self-auto">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Tambah Pelanggan
    </a>
</div>

{{-- Filter & Search Form (Dropdown & Button Style) --}}
<div class="mb-5">
    <form action="{{ route('customers.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 items-stretch md:items-center max-w-4xl">
        
        {{-- Input Pencarian Teks --}}
        <div class="relative flex-1 min-w-[240px]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z"/>
            </svg>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Cari nama, nomor telp, atau paket..."
                   class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm">
        </div>

        {{-- Dropdown Kriteria Sorting (Sort By) --}}
        <div class="relative min-w-[180px]">
            <select name="sort_by" 
                    class="w-full pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm appearance-none cursor-pointer">
                <option value="name" {{ $sortBy === 'name' ? 'selected' : '' }}>Sort by: Pelanggan</option>
                <option value="internet_package" {{ $sortBy === 'internet_package' ? 'selected' : '' }}>Sort by: Paket Internet</option>
                <option value="package_price" {{ $sortBy === 'package_price' ? 'selected' : '' }}>Sort by: Harga Paket</option>
                <option value="status" {{ $sortBy === 'status' ? 'selected' : '' }}>Sort by: Status</option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </div>
        </div>

        {{-- Dropdown Arah Urutan (Sort Order Asc/Desc) --}}
        <div class="relative min-w-[140px]">
            <select name="sort_order" 
                    class="w-full pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm appearance-none cursor-pointer">
                <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>A - Z (Ascending)</option>
                <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Z - A (Descending)</option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </div>
        </div>

        {{-- Tombol Aksi Filter --}}
        <div class="flex gap-2 shrink-0">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-sm shadow-blue-200 cursor-pointer">
                Filter
            </button>
            
            @if($search || $sortBy !== 'name' || $sortOrder !== 'asc')
                <a href="{{ route('customers.index') }}"
                   class="bg-white hover:bg-gray-50 text-gray-600 text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 transition flex items-center shadow-sm">
                    Reset
                </a>
            @endif
        </div>

    </form>
</div>

{{-- Alerts --}}
@if(session('success'))
<div class="mb-5 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl">
    <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- Table card --}}
<div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

    {{-- Desktop table --}}
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/70">
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <a href="{{ route('customers.index', ['sort_by' => 'name', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}"
                           class="inline-flex items-center gap-1 hover:text-blue-600 transition">
                            Pelanggan
                            @if($sortBy === 'name')
                                <span class="text-blue-500">{{ $sortOrder === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">No. Telepon</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <a href="{{ route('customers.index', ['sort_by' => 'internet_package', 'sort_order' => $sortOrder === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}"
                           class="inline-flex items-center gap-1 hover:text-blue-600 transition">
                            Paket Internet
                            @if($sortBy === 'internet_package')
                                <span class="text-blue-500">{{ $sortOrder === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Harga Paket</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($customers as $customer)
                <tr class="hover:bg-blue-50/30 transition-colors duration-100">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $customer->name }}</p>
                                <p class="text-xs text-gray-400 max-w-xs truncate">{{ $customer->address }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $customer->phone }}</td>
                    <td class="px-6 py-4 font-medium text-gray-700">{{ $customer->internet_package }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">Rp {{ number_format($customer->package_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @php $statusStr = trim(strtolower($customer->status)); @endphp
                        @if($statusStr === 'aktif')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Aktif</span>
                        @elseif($statusStr === 'suspend')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">Suspend</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">Putus</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center gap-1">
                            <a href="{{ route('customers.edit', $customer->id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                                </svg>
                                Edit
                            </a>
                            @if(Auth::user()->role === 'administrator')
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin melakukan soft-delete pelanggan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                            <p class="text-sm font-medium">Tidak ada data pelanggan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="sm:hidden divide-y divide-gray-100">
        @forelse($customers as $customer)
        <div class="px-4 py-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-sm font-bold shrink-0">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-gray-900 truncate">{{ $customer->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $customer->phone }}</p>
                </div>
                @php $statusStr = trim(strtolower($customer->status)); @endphp
                @if($statusStr === 'aktif')
                    <span class="shrink-0 px-2 py-0.5 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Aktif</span>
                @elseif($statusStr === 'suspend')
                    <span class="shrink-0 px-2 py-0.5 rounded-md text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">Suspend</span>
                @else
                    <span class="shrink-0 px-2 py-0.5 rounded-md text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">Putus</span>
                @endif
            </div>
            <div class="mb-2 space-y-1">
                <p class="text-xs text-gray-500 truncate">{{ $customer->address }}</p>
                <p class="text-xs text-gray-600 font-medium">{{ $customer->internet_package }} &mdash; <span class="font-semibold text-gray-800">Rp {{ number_format($customer->package_price, 0, ',', '.') }}</span></p>
            </div>
            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('customers.edit', $customer->id) }}"
                   class="text-xs font-semibold text-blue-600 hover:text-blue-700 px-3 py-1.5 bg-blue-50 rounded-lg transition">
                    Edit
                </a>
                @if(Auth::user()->role === 'administrator')
                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="text-xs font-semibold text-rose-600 hover:text-rose-700 px-3 py-1.5 bg-rose-50 rounded-lg transition">
                        Hapus
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="px-4 py-12 text-center">
            <p class="text-sm text-gray-400 font-medium">Tidak ada data pelanggan.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($customers->hasPages())
    <div class="px-5 py-3.5 border-t border-gray-100 ">
        {{ $customers->links() }}
    </div>
    @endif
</div>

@endsection