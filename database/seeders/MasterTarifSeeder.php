<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterTarifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::first();

        \App\Models\MasterTarif::create([
            'user_id' => $user->id,
            'jenis_kendaraan' => 'Motor',
            'tipe_tarif' => 'per_jam',
            'tarif_per_jam' => 5000,
            'tarif_flat' => 10000,
        ]);

        \App\Models\MasterTarif::create([
            'user_id' => $user->id,
            'jenis_kendaraan' => 'Mobil',
            'tipe_tarif' => 'per_jam',
            'tarif_per_jam' => 10000,
            'tarif_flat' => 25000,
        ]);

        \App\Models\MasterTarif::create([
            'user_id' => $user->id,
            'jenis_kendaraan' => 'Bus',
            'tipe_tarif' => 'flat',
            'tarif_per_jam' => 15000,
            'tarif_flat' => 50000,
        ]);
    }
}
