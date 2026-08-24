<?php

namespace App\Filament\Resources\PintuKeluars\Tables;

use App\Models\MasterTarif;
use App\Models\PintuKeluar;
use App\Models\PintuMasuk;
use Carbon\Carbon;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class PintuKeluarsTable
{
    public static function configure(Table $table): Table
    {
        $calculateTotal = static function (PintuMasuk $record): float {
            $duration = max(1, (int) ceil(Carbon::parse($record->waktu_masuk)->diffInMinutes(now()) / 60));
            $tariff = $record->masterTarif ?: MasterTarif::first();

            return $tariff?->tipe_tarif === 'flat'
                ? (float) ($tariff->tarif_flat ?? 0)
                : $duration * (float) ($tariff?->tarif_per_jam ?? 2000);
        };

        return $table
            ->columns([
                TextColumn::make('kode_karcis')
                    ->label('Kode Karcis')
                    ->searchable(),

                TextColumn::make('plat_nomor')
                    ->label('Plat Nomor')
                    ->searchable(),

                TextColumn::make('waktu_masuk')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y - H:i')
                    ->sortable(),

                TextColumn::make('durasi_jam')
                    ->label('Durasi Berjalan')
                    ->state(fn (PintuMasuk $record): int => max(1, (int) ceil(Carbon::parse($record->waktu_masuk)->diffInMinutes(now()) / 60)))
                    ->suffix(' Jam'),

                TextColumn::make('total_bayar')
                    ->label('Total Biaya')
                    ->money('IDR')
                    ->state(fn (PintuMasuk $record): float => $calculateTotal($record)),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ])
            ->actions([
                Action::make('bayar')
                    ->label('Bayar')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->modalHeading(fn (PintuMasuk $record): string => 'Bayar Karcis '.$record->kode_karcis)
                    ->modalSubmitActionLabel('Simpan Pembayaran')
                    ->form(fn (PintuMasuk $record): array => [
                        TextInput::make('total_bayar')
                            ->label('Total Bayar')
                            ->numeric()
                            ->prefix('Rp')
                            ->default($calculateTotal($record))
                            ->readOnly()
                            ->dehydrated(),
                        TextInput::make('uang_bayar')
                            ->label('Uang Bayar')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->minValue(1)
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set, Get $get): void {
                                $set('kembalian', max(0, (float) $state - (float) $get('total_bayar')));
                            })
                            ->rule(function (Get $get) {
                                return function (string $attribute, $value, Closure $fail) use ($get): void {
                                    $totalBiaya = (float) $get('total_bayar');
                                    $uangBayar = (float) $value;

                                    if ($uangBayar < $totalBiaya) {
                                        $fail('Uang bayar tidak boleh kurang dari total biaya (Rp '.number_format($totalBiaya, 0, ',', '.').').');
                                    }
                                };
                            }),
                        TextInput::make('kembalian')
                            ->label('Kembalian')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->readOnly(),
                    ])
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

                            // Store PintuKeluar ID in session for notification access
                            session()->flash('keluar_id_for_print', $keluar->id);
                        });
                    })
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
                    }),

                Action::make('cetak_struk')
                    ->label('Cetak Struk')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->visible(fn (PintuMasuk $record): bool => $record->status === 'SELESAI' && $record->pintuKeluar)
                    ->url(fn (PintuMasuk $record): string => $record->pintuKeluar ? route('pintu-keluar.struk', $record->pintuKeluar->id) : '#')
                    ->openUrlInNewTab(),
            ]);
    }
}
