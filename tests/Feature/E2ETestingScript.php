<?php

namespace Tests\Feature;

use App\Models\MasterTarif;
use App\Models\PintuKeluar;
use App\Models\PintuMasuk;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class E2ETestingScript extends TestCase
{

    public function test_e2e_parking_system_flow()
    {
        // SKENARIO 1: Master Tarif
        echo "\n=== SKENARIO 1: Master Tarif ===\n";
        
        $user = User::factory()->create(['name' => 'Admin', 'email' => 'admin@test.com']);
        echo "✓ User created: {$user->id}\n";

        // Test Master Tarif Per Jam
        $tarifMotor = MasterTarif::create([
            'user_id' => $user->id,
            'jenis_kendaraan' => 'Motor',
            'tipe_tarif' => 'per_jam',
            'tarif_per_jam' => 5000,
            'tarif_flat' => 10000,
        ]);
        echo "✓ Master Tarif Motor (per_jam) created: {$tarifMotor->id}\n";

        // Test Master Tarif Flat
        $tarifBus = MasterTarif::create([
            'user_id' => $user->id,
            'jenis_kendaraan' => 'Bus',
            'tipe_tarif' => 'flat',
            'tarif_per_jam' => 15000,
            'tarif_flat' => 50000,
        ]);
        echo "✓ Master Tarif Bus (flat) created: {$tarifBus->id}\n";

        // SKENARIO 2: Pintu Masuk - Create Karcis
        echo "\n=== SKENARIO 2: Pintu Masuk ===\n";

        $karcis1 = PintuMasuk::create([
            'plat_nomor' => 'B 1234 ABC',
            'master_tarif_id' => $tarifMotor->id,
            'waktu_masuk' => Carbon::now(),
        ]);
        echo "✓ Karcis 1 created: {$karcis1->kode_karcis}\n";
        echo "  - Status: {$karcis1->status}\n";
        echo "  - Waktu Masuk: {$karcis1->waktu_masuk}\n";
        $this->assertEquals('MASUK', $karcis1->status, 'Default status should be MASUK');
        $this->assertNotNull($karcis1->kode_karcis, 'Kode Karcis should be auto-generated');

        $karcis2 = PintuMasuk::create([
            'plat_nomor' => 'B 5678 DEF',
            'master_tarif_id' => $tarifBus->id,
            'waktu_masuk' => Carbon::now()->subHours(2),
        ]);
        echo "✓ Karcis 2 created: {$karcis2->kode_karcis}\n";

        // SKENARIO 3: Pintu Keluar - Payment Flow
        echo "\n=== SKENARIO 3: Pintu Keluar (Bayar) ===\n";

        // Simulate payment for karcis1 (Motor, per_jam)
        $durasi1 = Carbon::parse($karcis1->waktu_masuk)->diffInMinutes(now());
        $durasi1_jam = max(1, (int) ceil($durasi1 / 60));
        $total1 = $durasi1_jam * $tarifMotor->tarif_per_jam;

        echo "Karcis 1 Payment Calc:\n";
        echo "  - Duration (minutes): {$durasi1}\n";
        echo "  - Duration (hours): {$durasi1_jam}\n";
        echo "  - Tariff Type: per_jam\n";
        echo "  - Total: Rp " . number_format($total1, 0, ',', '.') . "\n";

        $bayar1 = $total1 + 5000; // Extra Rp 5000
        $kembalian1 = $bayar1 - $total1;

        $payment1 = PintuKeluar::create([
            'pintu_masuk_id' => $karcis1->id,
            'user_id' => $user->id,
            'waktu_keluar' => now(),
            'durasi_jam' => $durasi1_jam,
            'total_biaya' => $total1,
            'total_bayar' => $total1,
            'uang_bayar' => $bayar1,
            'kembalian' => $kembalian1,
            'status_pembayaran' => 'Lunas',
        ]);
        echo "✓ Payment created for Karcis 1: {$payment1->id}\n";
        echo "  - Total: Rp " . number_format($payment1->total_bayar, 0, ',', '.') . "\n";
        echo "  - Uang Bayar: Rp " . number_format($payment1->uang_bayar, 0, ',', '.') . "\n";
        echo "  - Kembalian: Rp " . number_format($payment1->kembalian, 0, ',', '.') . "\n";
        $this->assertEquals($kembalian1, $payment1->kembalian, 'Kembalian should match');

        // Update PintuMasuk status
        $karcis1->update([
            'waktu_keluar' => now(),
            'durasi_jam' => $durasi1_jam,
            'total_bayar' => $total1,
            'status' => 'SELESAI',
        ]);
        $karcis1->refresh();
        echo "✓ Karcis 1 status updated: {$karcis1->status}\n";
        $this->assertEquals('SELESAI', $karcis1->status, 'Status should be SELESAI after payment');

        // Simulate payment for karcis2 (Bus, flat)
        $total2 = $tarifBus->tarif_flat; // Flat rate

        echo "\nKarcis 2 Payment Calc:\n";
        echo "  - Tariff Type: flat\n";
        echo "  - Total: Rp " . number_format($total2, 0, ',', '.') . "\n";

        $bayar2 = $total2; // Exact amount
        $kembalian2 = 0;

        $payment2 = PintuKeluar::create([
            'pintu_masuk_id' => $karcis2->id,
            'user_id' => $user->id,
            'waktu_keluar' => now(),
            'durasi_jam' => 2,
            'total_biaya' => $total2,
            'total_bayar' => $total2,
            'uang_bayar' => $bayar2,
            'kembalian' => $kembalian2,
            'status_pembayaran' => 'Lunas',
        ]);
        echo "✓ Payment created for Karcis 2: {$payment2->id}\n";
        echo "  - Total: Rp " . number_format($payment2->total_bayar, 0, ',', '.') . "\n";

        $karcis2->update([
            'waktu_keluar' => now(),
            'durasi_jam' => 2,
            'total_bayar' => $total2,
            'status' => 'SELESAI',
        ]);
        echo "✓ Karcis 2 status updated: SELESAI\n";

        // VALIDATION: Check that only MASUK status tickets are visible
        echo "\n=== VALIDATION ===\n";
        $masukTickets = PintuMasuk::where('status', 'MASUK')->get();
        $selesaiTickets = PintuMasuk::where('status', 'SELESAI')->get();

        echo "✓ Tickets with status MASUK: {$masukTickets->count()}\n";
        echo "✓ Tickets with status SELESAI: {$selesaiTickets->count()}\n";

        $this->assertEquals(0, $masukTickets->count(), 'No tickets should have MASUK status after payment');
        $this->assertEquals(2, $selesaiTickets->count(), 'Both tickets should have SELESAI status');

        // Check receipt data is complete
        $receipt = PintuKeluar::find($payment1->id);
        $receipt->load('pintuMasuk.masterTarif');
        echo "\n✓ Receipt data for Karcis 1:\n";
        echo "  - Kode Karcis: {$receipt->pintuMasuk->kode_karcis}\n";
        echo "  - Plat Nomor: {$receipt->pintuMasuk->plat_nomor}\n";
        echo "  - Jenis Kendaraan: {$receipt->pintuMasuk->masterTarif->jenis_kendaraan}\n";
        echo "  - Status Pembayaran: {$receipt->status_pembayaran}\n";

        $this->assertNotNull($receipt->pintuMasuk, 'Receipt should have related PintuMasuk');
        $this->assertNotNull($receipt->pintuMasuk->masterTarif, 'Receipt should have related MasterTarif');

        echo "\n=== ALL TESTS PASSED ===\n";
    }
}
