<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPelanggan = Customer::count();
        $tagihanAktif = Invoice::where('status_payment', 'Belum Bayar')->count();
        $tagihanLunas = Invoice::where('status_payment', 'Lunas')->count();
        $totalPendapatan = Invoice::where('status_payment', 'Lunas')->sum('nominal');

        // 1. Ambil data tagihan dari database
        $rawInvoiceData = Invoice::select('periode', DB::raw('count(*) as total'))
            ->groupBy('periode')
            ->get()
            ->pluck('total', 'periode') // Ubah format menjadi key-value pair, contoh: ['Mei 2026' => 15]
            ->toArray();

        // 2. daftar nama bulan Indonesia untuk pemetaan label
        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // 3. Ambil data bulan dan tahun saat ini secara dinamis
        $bulanSekarang = Carbon::now()->month; // Angka bulan berjalan saat ini (1 s/d 12)
        $tahunSekarang = Carbon::now()->year;  // Angka tahun saat ini

        $chartLabels = [];
        $chartValues = [];

        // 4. Lakukan looping dari bulan 1 sampai bulan saat ini
        for ($m = 1; $m <= $bulanSekarang; $m++) {
            $keyPeriode = $daftarBulan[$m] . ' ' . $tahunSekarang;
            
            $chartLabels[] = $keyPeriode;
            
            // ambil jumlah data. Jika tidak ada, set ke 0.
            $chartValues[] = $rawInvoiceData[$keyPeriode] ?? 0;
        }

        return view('dashboard', compact(
            'totalPelanggan', 
            'tagihanAktif', 
            'tagihanLunas', 
            'totalPendapatan',
            'chartLabels', // Kirim data array label
            'chartValues'  // Kirim data array nilai total tagihan
        ));
    }
}