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
        Schema::create('pintu_masuks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('kode_karcis')->unique();
            $table->string('plat_nomor', 15);
            $table->dateTime('waktu_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pintu_masuks');
    }
};
