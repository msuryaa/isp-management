<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_pelanggan', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name');
            $blueprint->string('phone');
            $blueprint->text('address');
            $blueprint->string('internet_package');
            $blueprint->decimal('package_price', 12, 2); // Menggunakan decimal agar aman untuk nominal uang
            $blueprint->enum('status', ['aktif', 'suspend', 'putus']); // Sesuai rule di CustomerController
            $blueprint->timestamps();
            $blueprint->softDeletes(); // Wajib karena Model Customer menggunakan trait SoftDeletes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pelanggan');
    }
};