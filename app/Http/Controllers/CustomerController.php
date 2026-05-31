<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    //show list customer di halaman index dengan fitur search & sorting
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'name'); 
        $sortOrder = $request->get('sort_order', 'asc');

        // Proteksi kolom sorting agar aman dari SQL injection
        $allowedSorts = ['name', 'status', 'internet_package', 'package_price'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'name';
        }

        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('internet_package', 'like', "%{$search}%");
            })
            ->orderBy($sortBy, $sortOrder)
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', compact('customers', 'search', 'sortBy', 'sortOrder'));
    }

    //redirect ke halaman create customer
    public function create()
    {
        return view('customers.create');
    }

    //simpan data customer baru ke database dengan validasi
    public function store(Request $request)
    {
        // Aturan Validasi Server-Side
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'phone' => 'required|string|min:9|max:20|regex:/^[0-9+\-\s]+$/',
            'address' => 'required|string|min:10',
            'internet_package' => 'required|string|max:255',
            'package_price' => 'required|numeric|min:1000',
            'status' => ['required', Rule::in(['aktif', 'suspend', 'putus'])],
        ], [
            // Kustom Pesan Validasi Bahasa Indonesia
            'name.required' => 'Nama pelanggan wajib diisi.',
            'name.min' => 'Nama pelanggan minimal berisi 3 karakter.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.regex' => 'Format nomor telepon tidak valid (hanya angka, spasi, - dan +).',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'address.min' => 'Alamat lengkap minimal berisi 10 karakter.',
            'internet_package.required' => 'Nama paket internet wajib diisi.',
            'package_price.required' => 'Harga paket internet wajib diisi.',
            'package_price.numeric' => 'Harga paket harus berupa angka saja.',
            'package_price.min' => 'Harga paket minimal Rp 1.000.',
            'status.required' => 'Status pelanggan wajib dipilih.',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Data pelanggan baru berhasil disimpan.');
    }

    //redirect ke halaman edit customer
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    //update data customer di database dengan validasi
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'phone' => 'required|string|min:9|max:20|regex:/^[0-9+\-\s]+$/',
            'address' => 'required|string|min:10',
            'internet_package' => 'required|string|max:255',
            'package_price' => 'required|numeric|min:1000',
            'status' => ['required', Rule::in(['aktif', 'suspend', 'putus'])],
        ], [
            'name.required' => 'Nama pelanggan wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'internet_package.required' => 'Nama paket internet wajib diisi.',
            'package_price.required' => 'Harga paket internet wajib diisi.',
            'status.required' => 'Status pelanggan wajib dipilih.',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    //soft delete data customer di database dengan proteksi hanya admin yang bisa menghapus 
    public function destroy(Customer $customer)
    {
        if (Auth::user()->role !== 'administrator') {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus pelanggan.');
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil di-soft delete.');
    }
}