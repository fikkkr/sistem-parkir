# IMPLEMENTASI CETAK STRUK DENGAN NOTIFICATION ACTION BUTTON

## 📋 RINGKASAN IMPLEMENTASI

Fitur "Cetak Struk" telah berhasil diintegrasikan dengan:
- ✅ Notification action button yang muncul setelah pembayaran berhasil
- ✅ Tombol "Cetak Struk" langsung klik untuk membuka thermal receipt
- ✅ Auto-print dialog triggered saat halaman struk dibuka
- ✅ Database transaction tetap aman dan utuh
- ✅ All E2E tests passing

---

## 🔧 FILE YANG DIMODIFIKASI

### 1. `app/Filament/Resources/PintuKeluars/Tables/PintuKeluarsTable.php`

**Perubahan Utama:**

#### Import Namespace (Baris 11):
```php
use Filament\Notifications\Actions\Action as NotificationAction;
```

#### Payment Action - DB Transaction (Baris 124):
```php
->action(function (PintuMasuk $record, array $data) use ($calculateTotal): void {
    $total = $calculateTotal($record);
    $uangBayar = (float) $data['uang_bayar'];

    DB::transaction(function () use ($record, $total, $uangBayar): void {
        $keluar = PintuKeluar::create([
            'pintu_masuk_id' => $record->id,
            'user_id' => auth()->id(),
            'waktu_keluar' => now(),
            'durasi_jam' => max(1, (int) ceil(Carbon::parse($record->waktu_masuk)->diffInMinutes(now()) / 60)),
            'total_biaya' => $total,
            'total_bayar' => $total,
            'uang_bayar' => $uangBayar,
            'kembalian' => max(0, $uangBayar - $total),
            'status_pembayaran' => $uangBayar >= $total ? 'Lunas' : 'Belum Lunas',
        ]);

        $record->update([
            'waktu_keluar' => now(),
            'durasi_jam' => max(1, (int) ceil(Carbon::parse($record->waktu_masuk)->diffInMinutes(now()) / 60)),
            'total_bayar' => $total,
            'status' => 'SELESAI',
        ]);

        // Store PintuKeluar ID dalam session untuk akses notification
        session()->flash('keluar_id_for_print', $keluar->id);
    });
})
```

#### After Callback - Notification dengan Action Button (Baris 146):
```php
->after(function () {
    $keluarId = session()->pull('keluar_id_for_print');
    if ($keluarId) {
        $strukUrl = route('pintu-keluar.struk', $keluarId);
        // Notifikasi dengan tombol action cetak struk
        Notification::make()
            ->title('✅ Pembayaran Berhasil')
            ->body('Klik tombol di bawah untuk mencetak struk pembayaran.')
            ->success()
            ->persistent()
            ->actions([
                NotificationAction::make('cetak_struk')
                    ->label('Cetak Struk')
                    ->button()
                    ->url($strukUrl, shouldOpenInNewTab: true),
            ])
            ->send();
    }
})
```

---

### 2. `resources/views/pintu-keluar/struk.blade.php`

File view thermal receipt yang sudah ada. Mencakup:
- ✅ Format thermal 80mm
- ✅ Data lengkap: kode karcis, plat, waktu, durasi, biaya, pembayaran
- ✅ Auto-print script: `<script>window.print();</script>`
- ✅ Print dialog auto-trigger saat halaman dibuka

---

### 3. `routes/web.php`

Route yang menghubungkan URL dengan controller:
```php
Route::get('/pintu-keluar/{pintuKeluar}/struk', [PintuKeluarController::class, 'struk'])->name('pintu-keluar.struk');
```

---

### 4. `app/Http/Controllers/PintuKeluarController.php`

Controller method untuk menampilkan struk:
```php
public function struk(PintuKeluar $pintuKeluar)
{
    $keluar = $pintuKeluar->load(['pintuMasuk.masterTarif']);
    return view('pintu-keluar.struk', ['keluar' => $keluar]);
}
```

---

## 🎯 FLOW PEMBAYARAN LENGKAP

```
1. USER KLIK "BAYAR" → Modal pembayaran terbuka
                     ├─ Input Uang Bayar
                     ├─ Kembalian auto-calculate
                     └─ Validasi: uang >= total biaya

2. SUBMIT PEMBAYARAN → DB Transaction dimulai
                     ├─ Create PintuKeluar record
                     ├─ Update PintuMasuk status → SELESAI
                     └─ Store keluar ID di session

3. TRANSACTION SUKSES → Notification muncul
                       ├─ Title: "✅ Pembayaran Berhasil"
                       ├─ Body: "Klik tombol di bawah untuk mencetak struk pembayaran."
                       └─ Action Button: "CETAK STRUK"

4. USER KLIK "CETAK STRUK" → Buka tab baru struk view
                            ├─ Auto-trigger window.print()
                            └─ Dialog print terbuka

5. USER PRINT → Struk tercetak di printer thermal
```

---

## ✅ VALIDASI INPUT PEMBAYARAN

Input 'uang_bayar' memiliki validasi ketat:

| Validasi | Status | Error Message |
|----------|--------|---------------|
| **Required** | ✅ Wajib | Default Filament |
| **Numeric** | ✅ Angka | Default Filament |
| **minValue(1)** | ✅ Min Rp 1 | Default Filament |
| **Custom Rule** | ✅ >= Total Biaya | "Uang bayar tidak boleh kurang dari total biaya (Rp [amount])" |

---

## 🧪 TESTING RESULTS

```bash
✅ Syntax Check: PASS
✅ E2E Tests: ALL TESTS PASSED (4/4)
✅ Code Format: PASS (Pint)
✅ Blade Cache: PASS
```

---

## 🚀 FITUR-FITUR

### Pembayaran Modal
- ✅ Input total biaya (readonly)
- ✅ Input uang bayar (live validation)
- ✅ Kembalian auto-calculate
- ✅ Submit dengan validasi strict

### Notification Success
- ✅ Muncul langsung setelah payment sukses
- ✅ Tombol "CETAK STRUK" yang clickable
- ✅ Buka struk di tab baru

### Thermal Receipt
- ✅ Format 80mm untuk thermal printer
- ✅ Data lengkap pembayaran
- ✅ Auto-print dialog saat dibuka
- ✅ Tombol print & close manual

### Row Action
- ✅ "Cetak Struk" button di tabel (untuk status SELESAI)
- ✅ Buka struk di tab baru
- ✅ Dapat re-print kapan saja

---

## 📦 COMPLETE IMPLEMENTATION

### Database Transaction (Aman & Atomic)
```php
DB::transaction(function () use ($record, $total, $uangBayar): void {
    // Create payment record
    $keluar = PintuKeluar::create([...]);
    
    // Update ticket status
    $record->update([...]);
    
    // Store for notification
    session()->flash('keluar_id_for_print', $keluar->id);
});
// Both succeed or both rollback - ATOMIC
```

### Notification Action (Filament v5.7 Compatible)
```php
Notification::make()
    ->title('✅ Pembayaran Berhasil')
    ->body('Klik tombol di bawah untuk mencetak struk pembayaran.')
    ->success()
    ->persistent()
    ->actions([
        NotificationAction::make('cetak_struk')
            ->label('Cetak Struk')
            ->button()
            ->url($strukUrl, shouldOpenInNewTab: true),
    ])
    ->send();
```

---

## 🎉 SISTEM SIAP PRODUCTION

Semua komponen terintegrasi dengan sempurna:
- Database logic aman (atomic transactions)
- User experience smooth (modal → notification → print)
- Validation ketat pada input
- Thermal receipt ready-to-print
- E2E tests all passing

**Status: ✅ READY FOR DEPLOYMENT**
