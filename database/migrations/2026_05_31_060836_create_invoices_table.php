<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_tagihan', function (Blueprint $blueprint) {
            $blueprint->id();
            // Relasi Foreign Key ke tb_pelanggan
            $blueprint->foreignId('id_pelanggan')
                      ->constrained('tb_pelanggan')
                      ->onDelete('cascade'); 
            
            $blueprint->string('periode', 50);
            $blueprint->decimal('nominal', 12, 2);
            $blueprint->enum('status_payment', ['Belum Bayar', 'Lunas'])->default('Belum Bayar');
            $blueprint->datetime('payment_date')->nullable(); // Nullable karena diisi hanya saat sudah lunas
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_tagihan');
    }
};