<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('laporans', 'pintu_keluar_id')) {
            Schema::table('laporans', function (Blueprint $table): void {
                $table->foreignId('pintu_keluar_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('pintu_keluars')
                    ->onDelete('cascade');
            });
        }

        if (! Schema::hasColumn('laporans', 'user_id')) {
            Schema::table('laporans', function (Blueprint $table): void {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('pintu_keluar_id');
            });
        }

        if ($this->foreignKeyExists('laporans_user_id_foreign')) {
            Schema::table('laporans', function (Blueprint $table): void {
                $table->dropForeign(['user_id']);
            });
        }

        Schema::table('laporans', function (Blueprint $table): void {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        if (! $this->foreignKeyExists('laporans_user_id_foreign')) {
            Schema::table('laporans', function (Blueprint $table): void {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table): void {
            $table->dropForeign(['pintu_keluar_id']);
            $table->dropColumn('pintu_keluar_id');

            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    private function foreignKeyExists(string $name): bool
    {
        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'laporans')
            ->where('CONSTRAINT_NAME', $name)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();
    }
};