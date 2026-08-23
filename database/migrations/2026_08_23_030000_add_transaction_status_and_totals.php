<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pintu_masuks', function (Blueprint $table): void {
            $table->dateTime('waktu_keluar')->nullable()->after('waktu_masuk');
            $table->unsignedInteger('durasi_jam')->default(0)->after('waktu_keluar');
            $table->decimal('total_bayar', 10, 2)->default(0)->after('durasi_jam');
            $table->enum('status', ['MASUK', 'SELESAI'])->default('MASUK')->after('total_bayar');
        });

        Schema::table('pintu_keluars', function (Blueprint $table): void {
            $table->decimal('total_bayar', 10, 2)->default(0)->after('total_biaya');
        });
    }

    public function down(): void
    {
        Schema::table('pintu_keluars', function (Blueprint $table): void {
            $table->dropColumn('total_bayar');
        });

        Schema::table('pintu_masuks', function (Blueprint $table): void {
            $table->dropColumn(['waktu_keluar', 'durasi_jam', 'total_bayar', 'status']);
        });
    }
};
