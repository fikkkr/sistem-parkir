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
        Schema::table('master_tarifs', function (Blueprint $table) {
            //
            $table->string('tipe_tarif')->default('per_jam')->after('jenis_kendaraan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_tarifs', function (Blueprint $table) {
            //
            $table->dropColumn('tipe_tarif');
        });
    }
};
