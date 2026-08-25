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
        // Tambahkan index pada kolom yang sering digunakan untuk pencarian dan filter
        Schema::table('pintu_masuks', function (Blueprint $table) {
            $table->index('status'); // Untuk filtering status MASUK
            $table->index('waktu_masuk'); // Untuk sorting waktu masuk
            $table->index('plat_nomor'); // Untuk pencarian plat nomor
            $table->index('master_tarif_id'); // Untuk eager loading dengan masterTarif
        });

        Schema::table('pintu_keluars', function (Blueprint $table) {
            $table->index('pintu_masuk_id'); // Untuk join dengan pintu_masuks
            $table->index('waktu_keluar'); // Untuk sorting waktu keluar
            $table->index('status_pembayaran'); // Untuk filter status pembayaran
        });

        Schema::table('master_tarifs', function (Blueprint $table) {
            $table->index('tipe_tarif'); // Untuk filtering tipe tarif
            $table->index('jenis_kendaraan'); // Untuk filter jenis kendaraan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pintu_masuks', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['waktu_masuk']);
            $table->dropIndex(['plat_nomor']);
            $table->dropIndex(['master_tarif_id']);
        });

        Schema::table('pintu_keluars', function (Blueprint $table) {
            $table->dropIndex(['pintu_masuk_id']);
            $table->dropIndex(['waktu_keluar']);
            $table->dropIndex(['status_pembayaran']);
        });

        Schema::table('master_tarifs', function (Blueprint $table) {
            $table->dropIndex(['tipe_tarif']);
            $table->dropIndex(['jenis_kendaraan']);
        });
    }
};