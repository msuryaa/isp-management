@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')

{{-- Page header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Daftar Pengguna</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola administrator dan staff lapangan.</p>
    </div>
    <a href="{{ route('users.create') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition shadow-sm shadow-blue-200 self-start sm:self-auto">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Tambah User
    </a>
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
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengguna</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Created At</th>
                    <th class="px-6 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @foreach($users as $user)
                <tr class="hover:bg-blue-50/30 transition-colors duration-100">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->role === 'administrator')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                Administrator
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                Staff
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="inline-flex items-center gap-1">
                            <a href="{{ route('users.edit', $user->id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
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
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="sm:hidden divide-y divide-gray-100">
        @foreach($users as $user)
        <div class="px-4 py-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-sm font-bold shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                </div>
                @if($user->role === 'administrator')
                    <span class="shrink-0 px-2 py-0.5 rounded-md text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">Admin</span>
                @else
                    <span class="shrink-0 px-2 py-0.5 rounded-md text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">Staff</span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-400">{{ $user->created_at->format('d M Y') }}</p>
                <div class="flex gap-2">
                    <a href="{{ route('users.edit', $user->id) }}"
                       class="text-xs font-semibold text-blue-600 hover:text-blue-700 px-3 py-1.5 bg-blue-50 rounded-lg transition">
                        Edit
                    </a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline"
                          onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-700 px-3 py-1.5 bg-rose-50 rounded-lg transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/50" id="pagination-wrap">
        {{ $users->links() }}
    </div>
</div>

@endsection