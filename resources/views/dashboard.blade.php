@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Header --}}
<div class="mb-8">
    <h1 class="font-display text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
        Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }} !
    </h1>
    <p class="text-sm text-gray-500 mt-1.5 flex items-center gap-1.5">
        Masuk sebagai {{ Auth::user()->role }}
    </p>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
    @php
        $stats = [
            ['label' => 'Total Pelanggan',  'value' => $totalPelanggan, 'icon' => 'M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'blue', 'desc' => 'Pelanggan aktif'],
            ['label' => 'Tagihan Aktif',   'value' => $tagihanAktif, 'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z', 'color' => 'indigo', 'desc' => 'Belum membayar'],
            ['label' => 'Tagihan Lunas',   'value' => $tagihanLunas, 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'emerald', 'desc' => 'Sudah bayar bulan ini'],
            ['label' => 'Total Pendapatan','value' => 'Rp ' . number_format($totalPendapatan, 0, ',', '.'), 'icon' => 'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'violet', 'desc' => 'Total dana masuk'],
        ];

        $colors = [
            'blue'    => ['bg' => 'bg-blue-50',    'icon' => 'text-blue-600',    'border' => 'border-blue-100'],
            'indigo'  => ['bg' => 'bg-indigo-50',  'icon' => 'text-indigo-600',  'border' => 'border-indigo-100'],
            'emerald' => ['bg' => 'bg-emerald-50', 'icon' => 'text-emerald-600', 'border' => 'border-emerald-100'],
            'violet'  => ['bg' => 'bg-violet-50',  'icon' => 'text-violet-600',  'border' => 'border-violet-100'],
        ];
    @endphp

    @foreach($stats as $stat)
    @php $c = $colors[$stat['color']]; @endphp
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $stat['label'] }}</p>
                <p class="font-display text-2xl font-bold text-gray-900 mt-2">{{ $stat['value'] }}</p>
            </div>
            <div class="{{ $c['bg'] }} {{ $c['border'] }} border w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 {{ $c['icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                </svg>
            </div>
        </div>
        {{-- Mengubah teks statis "Data belum tersedia" menjadi dinamis --}}
        <p class="text-xs text-gray-400 mt-3">{{ $stat['desc'] }}</p>
    </div>
    @endforeach
</div>

{{-- Chart --}}
<div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h3 class="font-display text-base font-semibold text-gray-900">Jumlah Tagihan Per Bulan</h3>
            <p class="text-sm text-gray-400 mt-0.5">Grafik tagihan akan ditampilkan di sini.</p>
        </div>
    </div>
    {{-- Elemen Canvas untuk Grafik --}}
    <div class="relative w-full h-64 sm:h-72">
        <canvas id="invoiceMonthlyChart"></canvas>
    </div>
</div>
{{-- Load Library Chart.js via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Konversi data array PHP dari Controller ke Array JavaScript JSON
        const labelsData = JSON.parse('{!! json_encode($chartLabels) !!}');
        const valuesData = JSON.parse('{!! json_encode($chartValues) !!}');

        // Jika data kosong, tampilkan default data kosong agar chart tidak error
        const labels = labelsData.length > 0 ? labelsData : ['Belum Ada Periode'];
        const dataValues = valuesData.length > 0 ? valuesData : [0];

        const ctx = document.getElementById('invoiceMonthlyChart').getContext('2d');
        
        // Buat Gradient Biru Premium untuk latar belakang grafik batang
        const blueGradient = ctx.createLinearGradient(0, 0, 0, 300);
        blueGradient.addColorStop(0, 'rgba(37, 99, 235, 0.25)'); // Blue-600 transparan atas
        blueGradient.addColorStop(1, 'rgba(37, 99, 235, 0.01)'); // Pudar di bawah

        new Chart(ctx, {
            type: 'line', // Tipe grafik
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Tagihan Terbit',
                    data: dataValues,
                    borderColor: '#2563eb',
                    borderWidth: 2.5,
                    backgroundColor: blueGradient,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.parsed.y} Tagihan`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false 
                        },
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            precision: 0,
                            color: '#9ca3af',
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    });
</script>

@endsection