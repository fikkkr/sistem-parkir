<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pintu_keluars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pintu_masuk_id')->constrained('pintu_masuks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('waktu_keluar');
            $table->integer('durasi_jam');
            $table->decimal('total_biaya', 10, 2);
            $table->decimal('uang_bayar', 10, 2);
            $table->decimal('kembalian', 10, 2);
            $table->enum('status_pembayaran', ['Lunas', 'Belum Lunas'])->default('Lunas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pintu_keluars');
    }
};
