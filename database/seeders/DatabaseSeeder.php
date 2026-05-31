<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. BUAT DATA USER (Akun untuk Login)
        // Membuat akun Administrator
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'administrator',
        ]);

        // Membuat akun Staff
        User::create([
            'name' => 'Staff Lapangan',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);


        // 2. BUAT DATA PELANGGAN (15 Data agar Pagination Muncul)
        $customerData = [
            ['name' => 'Ahmad Subarjo', 'phone' => '081234567890', 'address' => 'Jl. Merdeka No. 12, Jakarta', 'internet_package' => 'Home Basic 20 Mbps', 'package_price' => 200000, 'status' => 'aktif'],
            ['name' => 'Budi Setiadi', 'phone' => '082198765432', 'address' => 'Jl. Mawar Blk. C No. 4, Bandung', 'internet_package' => 'Home Premium 50 Mbps', 'package_price' => 350000, 'status' => 'aktif'],
            ['name' => 'Citra Lestari', 'phone' => '083811223344', 'address' => 'Griya Asri Kav. 9, Surabaya', 'internet_package' => 'Business Pro 100 Mbps', 'package_price' => 700000, 'status' => 'suspend'],
            ['name' => 'Dedi Wijaya', 'phone' => '085699887766', 'address' => 'Jl. Sudirman No. 88, Yogyakarta', 'internet_package' => 'Home Basic 20 Mbps', 'package_price' => 200000, 'status' => 'aktif'],
            ['name' => 'Eka Putri', 'phone' => '087755443322', 'address' => 'Perum Indah Blok A/10, Semarang', 'internet_package' => 'Home Starter 10 Mbps', 'package_price' => 150000, 'status' => 'putus'],
            ['name' => 'Fahmi Anwar', 'phone' => '081122334455', 'address' => 'Jl. Gatot Subroto No. 45, Medan', 'internet_package' => 'Home Premium 50 Mbps', 'package_price' => 350000, 'status' => 'aktif'],
            ['name' => 'Gita Permata', 'phone' => '081344556677', 'address' => 'Kampung Baru RT 02/RW 05, Makassar', 'internet_package' => 'Home Basic 20 Mbps', 'package_price' => 200000, 'status' => 'aktif'],
            ['name' => 'Hendra Kurniawan', 'phone' => '081988776655', 'address' => 'Jl. Diponegoro Gang 4, Malang', 'internet_package' => 'Business Pro 100 Mbps', 'package_price' => 700000, 'status' => 'aktif'],
            ['name' => 'Indah Sari', 'phone' => '085211335577', 'address' => 'Perumahan elite No. 1B, Palembang', 'internet_package' => 'Home Premium 50 Mbps', 'package_price' => 350000, 'status' => 'suspend'],
            ['name' => 'Joko Susilo', 'phone' => '089966442200', 'address' => 'Desa Makmur RT 01, Solo', 'internet_package' => 'Home Starter 10 Mbps', 'package_price' => 150000, 'status' => 'aktif'],
            ['name' => 'Kurnia Dewi', 'phone' => '081255556666', 'address' => 'Jl. Pahlawan No. 17, Denpasar', 'internet_package' => 'Home Basic 20 Mbps', 'package_price' => 200000, 'status' => 'aktif'],
            ['name' => 'Laksana Tri', 'phone' => '082277778888', 'address' => 'Apartemen Gading Lantai 5, Jakarta', 'internet_package' => 'Business Pro 100 Mbps', 'package_price' => 700000, 'status' => 'aktif'],
            ['name' => 'Megawati Saputri', 'phone' => '083199990000', 'address' => 'Jl. Pemuda No. 9, Balikpapan', 'internet_package' => 'Home Basic 20 Mbps', 'package_price' => 200000, 'status' => 'aktif'],
            ['name' => 'Nugroho Adi', 'phone' => '085733334444', 'address' => 'Perumahan Hijau Blok F, Bogor', 'internet_package' => 'Home Premium 50 Mbps', 'package_price' => 350000, 'status' => 'aktif'],
            ['name' => 'Oki Setiawan', 'phone' => '087822223333', 'address' => 'Jl. Asia Afrika No. 120, Bandung', 'internet_package' => 'Home Starter 10 Mbps', 'package_price' => 150000, 'status' => 'aktif'],
        ];

        foreach ($customerData as $data) {
            $customer = Customer::create($data);

            // 3. BUAT DATA TAGIHAN (Invoice) UNTUK MASING-MASING PELANGGAN
            // Setiap pelanggan otomatis dibuatkan 1 contoh tagihan
            $isLunas = rand(0, 1); // Acak status payment lunas atau belum

            Invoice::create([
                'id_pelanggan' => $customer->id,
                'periode' => 'Mei 2026',
                'nominal' => $customer->package_price,
                'status_payment' => $isLunas ? 'Lunas' : 'Belum Bayar',
                'payment_date' => $isLunas ? Carbon::now()->subDays(rand(1, 10)) : null,
            ]);
        }
    }
}
