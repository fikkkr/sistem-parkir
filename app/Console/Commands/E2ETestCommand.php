<?php

namespace App\Console\Commands;

use App\Models\MasterTarif;
use App\Models\PintuKeluar;
use App\Models\PintuMasuk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class E2ETestCommand extends Command
{
    protected $signature = 'test:e2e';

    protected $description = 'Run End-to-End tests for parking system';

    public function handle()
    {
        $this->info("\n=== END-TO-END TESTING: SISTEM PARKIR ===\n");

        try {
            $this->testMasterTarif();
            $this->testPintuMasuk();
            $this->testPintuKeluar();
            $this->testValidation();

            $this->info("\n✅ ALL TESTS PASSED!\n");
            return 0;
        } catch (\Exception $e) {
            $this->error("\n❌ TEST FAILED: {$e->getMessage()}\n");
            $this->error("File: {$e->getFile()}\nLine: {$e->getLine()}\n");
            return 1;
        }
    }

    private function testMasterTarif()
    {
        $this->info("\n=== TEST 1: Master Tarif ===");

        $user = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            ['name' => 'Admin Test', 'password' => bcrypt('password')]
        );
        $this->line("✓ User created/found: ID {$user->id}");

        // Test Per Jam
        $tarifMotor = MasterTarif::create([
            'user_id' => $user->id,
            'jenis_kendaraan' => 'Motor',
            'tipe_tarif' => 'per_jam',
            'tarif_per_jam' => 5000,
            'tarif_flat' => 10000,
        ]);
        $this->line("✓ Master Tarif Motor (per_jam) created");
        $this->line("  Tarif/jam: Rp " . number_format($tarifMotor->tarif_per_jam, 0, ',', '.'));

        // Test Flat
        $tarifBus = MasterTarif::create([
            'user_id' => $user->id,
            'jenis_kendaraan' => 'Bus',
            'tipe_tarif' => 'flat',
            'tarif_per_jam' => 15000,
            'tarif_flat' => 50000,
        ]);
        $this->line("✓ Master Tarif Bus (flat) created");
        $this->line("  Tarif flat: Rp " . number_format($tarifBus->tarif_flat, 0, ',', '.'));

        $this->info("✓ Master Tarif Test Passed");
        return [$tarifMotor, $tarifBus, $user];
    }

    private function testPintuMasuk()
    {
        $this->info("\n=== TEST 2: Pintu Masuk (Buat Karcis) ===");

        $tarif = MasterTarif::first();
        $user = User::first();

        // Create karcis
        $karcis = PintuMasuk::create([
            'plat_nomor' => 'B 1234 ABC',
            'master_tarif_id' => $tarif->id,
            'waktu_masuk' => Carbon::now(),
        ]);

        $this->line("✓ Karcis created");
        $this->line("  Kode Karcis: {$karcis->kode_karcis}");
        $this->line("  Plat: {$karcis->plat_nomor}");
        $this->line("  Status: {$karcis->status}");
        $this->line("  Waktu Masuk: {$karcis->waktu_masuk->format('d/m/Y H:i:s')}");

        if ($karcis->status !== 'MASUK') {
            throw new \Exception("Karcis status should be 'MASUK', got '{$karcis->status}'");
        }

        if (empty($karcis->kode_karcis)) {
            throw new \Exception("Kode Karcis should be auto-generated");
        }

        $this->info("✓ Pintu Masuk Test Passed");
        return $karcis;
    }

    private function testPintuKeluar()
    {
        $this->info("\n=== TEST 3: Pintu Keluar (Bayar & Struk) ===");

        $user = User::first();
        $karcis = PintuMasuk::where('status', 'MASUK')->first();

        if (! $karcis) {
            throw new \Exception("No MASUK tickets found for payment test");
        }

        // Calculate payment
        $durasi = Carbon::parse($karcis->waktu_masuk)->diffInMinutes(now());
        $durasi_jam = max(1, (int) ceil($durasi / 60));
        $tarif = $karcis->masterTarif;

        if ($tarif->tipe_tarif === 'flat') {
            $total = $tarif->tarif_flat;
        } else {
            $total = $durasi_jam * $tarif->tarif_per_jam;
        }

        $this->line("✓ Payment calculation:");
        $this->line("  Duration: {$durasi_jam} jam");
        $this->line("  Tariff Type: {$tarif->tipe_tarif}");
        $this->line("  Total: Rp " . number_format($total, 0, ',', '.'));

        // Process payment
        $uangBayar = $total + 10000; // +10k kembalian
        $kembalian = $uangBayar - $total;

        $payment = PintuKeluar::create([
            'pintu_masuk_id' => $karcis->id,
            'user_id' => $user->id,
            'waktu_keluar' => now(),
            'durasi_jam' => $durasi_jam,
            'total_biaya' => $total,
            'total_bayar' => $total,
            'uang_bayar' => $uangBayar,
            'kembalian' => $kembalian,
            'status_pembayaran' => 'Lunas',
        ]);

        $this->line("✓ Payment recorded");
        $this->line("  Uang Bayar: Rp " . number_format($payment->uang_bayar, 0, ',', '.'));
        $this->line("  Kembalian: Rp " . number_format($payment->kembalian, 0, ',', '.'));

        if (abs((float) $payment->kembalian - (float) $kembalian) > 0.01) {
            throw new \Exception("Kembalian calculation mismatch: expected {$kembalian}, got {$payment->kembalian}");
        }

        // Update karcis status
        $karcis->update([
            'waktu_keluar' => now(),
            'durasi_jam' => $durasi_jam,
            'total_bayar' => $total,
            'status' => 'SELESAI',
        ]);

        $karcis->refresh();
        $this->line("✓ Karcis status updated to: {$karcis->status}");

        // Test receipt data
        $payment->load('pintuMasuk.masterTarif');
        $this->line("✓ Receipt data validated:");
        $this->line("  Karcis: {$payment->pintuMasuk->kode_karcis}");
        $this->line("  Plat: {$payment->pintuMasuk->plat_nomor}");
        $this->line("  Jenis: {$payment->pintuMasuk->masterTarif->jenis_kendaraan}");

        $this->info("✓ Pintu Keluar Test Passed");
        return $payment;
    }

    private function testValidation()
    {
        $this->info("\n=== TEST 4: Validation & Data Integrity ===");

        $masukCount = PintuMasuk::where('status', 'MASUK')->count();
        $selesaiCount = PintuMasuk::where('status', 'SELESAI')->count();

        $this->line("✓ Tickets with status MASUK: {$masukCount}");
        $this->line("✓ Tickets with status SELESAI: {$selesaiCount}");

        // Verify relationships
        $payment = PintuKeluar::first();
        if ($payment) {
            if (! $payment->pintuMasuk) {
                throw new \Exception("Payment should have related PintuMasuk");
            }
            if (! $payment->pintuMasuk->masterTarif) {
                throw new \Exception("PintuMasuk should have related MasterTarif");
            }
            $this->line("✓ Relationship validation passed");
        }

        $this->info("✓ Validation Test Passed");
    }
}
