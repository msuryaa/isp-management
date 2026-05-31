@extends('layouts.app')

@section('title', 'Buat Tagihan Baru')

@section('content')

{{-- Back + Header --}}
<div class="mb-7">
    <a href="{{ route('invoices.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 font-medium transition mb-3 group">
        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Kembali ke Daftar
    </a>
    <h1 class="font-display text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Buat Tagihan Baru</h1>
    <p class="text-sm text-gray-500 mt-1">Gunakan formulir ini untuk menerbitkan tagihan baru kepada pelanggan aktif.</p>
</div>

<div class="w-full">
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm">
        <div class="p-6 sm:p-8">
            <form action="{{ route('invoices.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Pilih Pelanggan (Searchable) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Pilih Pelanggan
                        <span class="text-rose-500 ml-0.5">*</span>
                    </label>

                    {{-- Custom searchable dropdown --}}
                    <div class="relative" id="customerDropdown">
                        {{-- Hidden real input for form submission --}}
                        <input type="hidden" name="id_pelanggan" id="id_pelanggan" value="{{ old('id_pelanggan') }}" required>

                        {{-- Trigger display --}}
                        <button type="button" id="dropdownTrigger"
                                class="w-full flex items-center justify-between px-4 py-2.5 bg-white border @error('id_pelanggan') border-rose-400 bg-rose-50 @else border-gray-200 @enderror rounded-xl text-sm text-left focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                                onclick="toggleDropdown()">
                            <span id="dropdownLabel" class="text-gray-400">-- Pilih Pelanggan --</span>
                            <svg id="dropdownChevron" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown panel --}}
                        <div id="dropdownPanel"
                             class="hidden absolute z-20 mt-1.5 w-full bg-white border border-gray-200 rounded-xl shadow-lg">
                            {{-- Search box --}}
                            <div class="p-2 border-b border-gray-100">
                                <div class="relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z"/>
                                    </svg>
                                    <input type="text" id="customerSearch"
                                           placeholder="Cari nama pelanggan atau paket..."
                                           class="w-full pl-8 pr-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition"
                                           oninput="filterCustomers(this.value)"
                                           autocomplete="off">
                                </div>
                            </div>
                            {{-- Options list --}}
                            <ul id="customerList" class="max-h-60 overflow-y-auto py-1 overscroll-contain">
                                @foreach($customers as $customer)
                                <li class="customer-option px-4 py-2.5 cursor-pointer hover:bg-blue-50 transition-colors duration-100"
                                    data-id="{{ $customer->id }}"
                                    data-price="{{ $customer->package_price }}"
                                    data-label="{{ $customer->name }}"
                                    data-search="{{ strtolower($customer->name . ' ' . $customer->internet_package) }}"
                                    onclick="selectCustomer(this)">
                                    <p class="text-sm font-semibold text-gray-800">{{ $customer->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $customer->internet_package }} &mdash; Rp {{ number_format($customer->package_price, 0, ',', '.') }}</p>
                                </li>
                                @endforeach
                                <li id="noResults" class="hidden px-4 py-3 text-sm text-gray-400 text-center">
                                    Tidak ada pelanggan ditemukan.
                                </li>
                            </ul>
                        </div>
                    </div>

                    @error('id_pelanggan')
                        <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Grid Periode & Nominal --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Periode Tagihan
                            <span class="text-rose-500 ml-0.5">*</span>
                        </label>
                        <input type="text" name="periode"
                               value="{{ old('periode', \Illuminate\Support\Carbon::now()->translatedFormat('F Y')) }}"
                               placeholder="Contoh: Juni 2026"
                               class="w-full px-4 py-2.5 bg-white border rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition @error('periode') border-rose-400 bg-rose-50 @else border-gray-200 @enderror"
                               required>
                        @error('periode')
                            <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nominal Tagihan (IDR)
                            <span class="text-rose-500 ml-0.5">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium pointer-events-none">Rp</span>
                            <input type="number" name="nominal" id="nominal" value="{{ old('nominal') }}"
                                   placeholder="Otomatis dari paket pelanggan"
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition @error('nominal') border-rose-400 bg-rose-50 @else border-gray-200 @enderror"
                                   required>
                        </div>
                        @error('nominal')
                            <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
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
                        Terbitkan Tagihan
                    </button>
                    <a href="{{ route('invoices.index') }}"
                       class="text-sm font-semibold text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-100 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // ── Searchable Dropdown ──────────────────────────────────────────────────

    function toggleDropdown() {
        const panel = document.getElementById('dropdownPanel');
        const chevron = document.getElementById('dropdownChevron');
        const isHidden = panel.classList.contains('hidden');
        panel.classList.toggle('hidden', !isHidden);
        chevron.style.transform = isHidden ? 'rotate(180deg)' : '';
        if (isHidden) {
            setTimeout(() => document.getElementById('customerSearch').focus(), 50);
        }
    }

    function filterCustomers(query) {
        const q = query.toLowerCase();
        const items = document.querySelectorAll('.customer-option');
        let visible = 0;
        items.forEach(item => {
            const match = item.dataset.search.includes(q);
            item.classList.toggle('hidden', !match);
            if (match) visible++;
        });
        document.getElementById('noResults').classList.toggle('hidden', visible > 0);
    }

    function selectCustomer(el) {
        document.getElementById('id_pelanggan').value = el.dataset.id;
        document.getElementById('nominal').value = el.dataset.price;

        const label = document.getElementById('dropdownLabel');
        label.textContent = el.dataset.label;
        label.classList.remove('text-gray-400');
        label.classList.add('text-gray-900', 'font-medium');

        document.getElementById('dropdownPanel').classList.add('hidden');
        document.getElementById('dropdownChevron').style.transform = '';
        document.getElementById('customerSearch').value = '';
        filterCustomers('');
    }

    // Close on outside click
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('customerDropdown');
        if (!dropdown.contains(e.target)) {
            document.getElementById('dropdownPanel').classList.add('hidden');
            document.getElementById('dropdownChevron').style.transform = '';
        }
    });

    // Restore selection on validation error (Fixed syntax error)
    const savedId = "{{ old('id_pelanggan') }}";
    if (savedId) {
        const savedOption = document.querySelector(`.customer-option[data-id="${savedId}"]`);
        if (savedOption) selectCustomer(savedOption);
    }
</script>

@endsection