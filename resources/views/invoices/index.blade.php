@extends('layouts.app')

@section('title', 'Kelola Tagihan')

@section('content')

{{-- Page header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Tagihan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola seluruh tagihan bulanan pelanggan internet dan catat riwayat pembayarannya.</p>
    </div>
    <a href="{{ route('invoices.create') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition shadow-sm shadow-blue-200 self-start sm:self-auto">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Buat Tagihan
    </a>
</div>

{{-- Filter & Search --}}
<div class="mb-5">
    <form action="{{ route('invoices.index') }}" method="GET" class="flex flex-wrap gap-2 max-w-2xl">
        <div class="relative flex-1 min-w-[180px]">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z"/>
            </svg>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Cari nama pelanggan atau periode..."
                   class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm">
        </div>
        <select name="status"
                class="px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm">
            <option value="">Semua Status</option>
            <option value="Belum Bayar" {{ $status === 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
            <option value="Lunas" {{ $status === 'Lunas' ? 'selected' : '' }}>Lunas</option>
        </select>
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-sm shadow-blue-200">
            Filter
        </button>
        @if($search || $status)
            <a href="{{ route('invoices.index') }}"
               class="bg-white hover:bg-gray-50 text-gray-600 text-sm font-medium px-3 py-2.5 rounded-xl border border-gray-200 transition flex items-center">
                Reset
            </a>
        @endif
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
@if(session('error'))
<div class="mb-5 flex items-start gap-3 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-sm rounded-xl">
    <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
    </svg>
    {{ session('error') }}
</div>
@endif

{{-- Table card --}}
<div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

    {{-- Desktop table --}}
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/70">
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Periode</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Nominal</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal Bayar</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-blue-50/30 transition-colors duration-100">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr($invoice->customer->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $invoice->customer->name ?? 'Pelanggan Terhapus' }}</p>
                                <p class="text-xs text-gray-400">{{ $invoice->customer->phone ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-700">{{ $invoice->periode }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">Rp {{ number_format($invoice->nominal, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($invoice->status_payment === 'Lunas')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Lunas</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">Belum Bayar</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">
                        {{ $invoice->payment_date ? $invoice->payment_date->format('d M Y, H:i') : '—' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($invoice->status_payment === 'Belum Bayar')
                        <form action="{{ route('invoices.pay', $invoice->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Proses pembayaran tagihan ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                                </svg>
                                Bayar
                            </button>
                        </form>
                        @else
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-gray-400 cursor-not-allowed">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Terbayar
                        </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center gap-2 text-gray-400">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            <p class="text-sm font-medium">Tidak ada data tagihan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="sm:hidden divide-y divide-gray-100">
        @forelse($invoices as $invoice)
        <div class="px-4 py-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-sm font-bold shrink-0">
                    {{ strtoupper(substr($invoice->customer->name ?? '?', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-gray-900 truncate">{{ $invoice->customer->name ?? 'Pelanggan Terhapus' }}</p>
                    <p class="text-xs text-gray-400">{{ $invoice->customer->phone ?? '-' }}</p>
                </div>
                @if($invoice->status_payment === 'Lunas')
                    <span class="shrink-0 px-2 py-0.5 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Lunas</span>
                @else
                    <span class="shrink-0 px-2 py-0.5 rounded-md text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">Belum Bayar</span>
                @endif
            </div>
            <div class="mb-3 space-y-1">
                <p class="text-xs text-gray-500">Periode: <span class="font-medium text-gray-700">{{ $invoice->periode }}</span></p>
                <p class="text-xs text-gray-500">Nominal: <span class="font-semibold text-gray-900">Rp {{ number_format($invoice->nominal, 0, ',', '.') }}</span></p>
                <p class="text-xs text-gray-400">
                    {{ $invoice->payment_date ? 'Dibayar: ' . $invoice->payment_date->format('d M Y, H:i') : 'Belum ada pembayaran' }}
                </p>
            </div>
            <div class="flex items-center justify-end">
                @if($invoice->status_payment === 'Belum Bayar')
                <form action="{{ route('invoices.pay', $invoice->id) }}" method="POST" class="inline"
                      onsubmit="return confirm('Proses pembayaran tagihan ini?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                            class="text-xs font-semibold text-blue-600 hover:text-blue-700 px-3 py-1.5 bg-blue-50 rounded-lg transition">
                        Bayar Sekarang
                    </button>
                </form>
                @else
                <span class="text-xs font-semibold text-emerald-600 px-3 py-1.5 bg-emerald-50 rounded-lg">
                    ✓ Terbayar
                </span>
                @endif
            </div>
        </div>
        @empty
        <div class="px-4 py-12 text-center">
            <p class="text-sm text-gray-400 font-medium">Tidak ada data tagihan.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($invoices->hasPages())
    <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/50" id="pagination-wrap">
        {{ $invoices->links() }}
    </div>
    @endif
</div>

@endsection