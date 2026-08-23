<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pintu_masuks', function (Blueprint $table) {
            $table->foreignId('master_tarif_id')
                ->nullable()
                ->after('plat_nomor')
                ->constrained('master_tarifs')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pintu_masuks', function (Blueprint $table) {
            $table->dropForeign(['master_tarif_id']);
            $table->dropColumn('master_tarif_id');
        });
    }
};
