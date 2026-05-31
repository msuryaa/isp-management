<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InvoiceController extends Controller
{
    //show list tagihan di halaman index dengan fitur search dan filter status
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status'); // Menerima filter 'Belum Bayar' atau 'Lunas' dari index.blade.php

        $invoices = Invoice::with('customer')
            ->when($search, function ($query, $search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('periode', 'like', "%{$search}%");
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('status_payment', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('invoices.index', compact('invoices', 'search', 'status'));
    }

    // redirect ke halaman create tagihan dengan dropdown list pelanggan aktif
    public function create()
    {
        $customers = Customer::where('status', '!=', 'putus')->orderBy('name', 'asc')->get();
        return view('invoices.create', compact('customers'));
    }

    // simpan data tagihan baru ke database dengan validasi
    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:tb_pelanggan,id',
            'periode'      => 'required|string|max:50',
            'nominal'      => 'required|numeric|min:1000',
        ], [
            'id_pelanggan.required' => 'Pelanggan wajib dipilih.',
            'id_pelanggan.exists'   => 'Pelanggan yang dipilih tidak terdaftar.',
            'periode.required'      => 'Periode tagihan wajib diisi.',
            'nominal.required'      => 'Nominal tagihan wajib diisi.',
            'nominal.numeric'       => 'Nominal tagihan harus berupa angka.',
            'nominal.min'           => 'Nominal tagihan minimal Rp 1.000.',
        ]);

        // Simpan tagihan baru dengan status default "Belum Bayar"
        Invoice::create([
            'id_pelanggan'   => $request->id_pelanggan,
            'periode'        => $request->periode,
            'nominal'        => $request->nominal,
            'status_payment' => 'Belum Bayar', 
        ]);

        return redirect()->route('invoices.index')->with('success', 'Tagihan baru berhasil diterbitkan.');
    }

    // Proses pembayaran tagihan
    public function pay(Invoice $invoice)
    {
        // Cek jika tagihan sudah berstatus "Lunas" untuk mencegah pembayaran ganda
        if ($invoice->status_payment === 'Lunas') {
            return redirect()->route('invoices.index')->with('error', 'Tagihan ini sudah berstatus lunas.');
        }

        // Update status pembayaran menjadi "Lunas" dan simpan tanggal pembayaran saat ini
        $invoice->update([
            'status_payment' => 'Lunas',
            'payment_date'   => Carbon::now(),
        ]);

        return redirect()->route('invoices.index')->with('success', 'Pembayaran tagihan berhasil dicatat. Status berubah menjadi Lunas.');
    }
}