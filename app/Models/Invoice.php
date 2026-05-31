<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    // Menggunakan penamaan tabel kustom yang konsisten dengan tb_pelanggan
    protected $table = 'tb_tagihan';

    protected $fillable = [
        'id_pelanggan',
        'periode',
        'nominal',
        'status_payment',
        'payment_date',
    ];

    // Casts tipe data tanggal pembayaran agar otomatis dibaca sebagai objek Carbon/Datetime
    protected $casts = [
        'payment_date' => 'datetime',
    ];

    /**
     * Relasi belongsTo ke Model Customer (Satu tagihan dimiliki oleh satu pelanggan)
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_pelanggan');
    }
}