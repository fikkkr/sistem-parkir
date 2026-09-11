<?php

namespace App\Filament\Pages;

use App\Models\PintuMasuk;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class LaporanPage extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Laporan Pendapatan';
    protected string $view = 'filament.pages.laporan';
    protected static ?int $navigationSort = 99;

    public ?array $data = [];
    public $transaksiData;
    public $summaryData = [];

    public function mount(): void
    {
        $this->data = [
            'tanggal_mulai' => now()->startOfMonth()->format('Y-m-d'),
            'tanggal_selesai' => now()->endOfMonth()->format('Y-m-d'),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filter Rentang Tanggal')
                    ->components([
                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required()
                            ->default(Carbon::now()->startOfMonth())
                            ->prefixIcon('heroicon-m-calendar'),
                        
                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->default(Carbon::now())
                            ->prefixIcon('heroicon-m-calendar'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function generateLaporan(): void
    {
        $state = $this->form->getState();
        $tanggal_mulai = Carbon::parse($state['tanggal_mulai'])->startOfDay();
        $tanggal_selesai = Carbon::parse($state['tanggal_selesai'])->endOfDay();

        // Get completed transactions (SELESAI) within date range
        $this->transaksiData = PintuMasuk::with(['pintuKeluar.user'])
            ->where('status', 'SELESAI')
            ->whereHas('pintuKeluar', function ($query): void {
                $query->where('status_pembayaran', 'Lunas');
            })
            ->whereBetween('waktu_keluar', [$tanggal_mulai, $tanggal_selesai])
            ->orderBy('waktu_keluar', 'desc')
            ->get();

        $this->resetTable();

        // Calculate summary data
        $this->summaryData = [
            'total_transaksi' => $this->transaksiData->count(),
            'total_pendapatan' => $this->transaksiData->sum('total_bayar'),
            'tanggal_mulai' => $tanggal_mulai->format('d M Y'),
            'tanggal_selesai' => $tanggal_selesai->format('d M Y'),
        ];

        Notification::make()
            ->title('Laporan berhasil dibuat!')
            ->success()
            ->send();
    }

    public function exportToPDF(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->generateLaporan();
        
        $tanggal_mulai = $this->data['tanggal_mulai'];
        $tanggal_selesai = $this->data['tanggal_selesai'];

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf', [
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'transaksiSelesai' => $this->transaksiData,
            'totalTransaksi' => $this->summaryData['total_transaksi'],
            'totalPendapatan' => $this->summaryData['total_pendapatan'],
        ]);
        
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'laporan-pendapatan-' . $tanggal_mulai . '-to-' . $tanggal_selesai . '.pdf'
        );
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PintuMasuk::with(['pintuKeluar.user'])
                    ->where('status', 'SELESAI')
                    ->whereHas('pintuKeluar', function ($query): void {
                        $query->where('status_pembayaran', 'Lunas');
                    })
                    ->whereBetween('waktu_keluar', [
                        Carbon::parse($this->data['tanggal_mulai'])->startOfDay(),
                        Carbon::parse($this->data['tanggal_selesai'])->endOfDay(),
                    ])
                    ->orderBy('waktu_keluar', 'desc')
            )
            ->columns([
                TextColumn::make('kode_karcis')
                    ->label('Kode Karcis')
                    ->searchable()
                    ->summarize(Count::make()->label('Total Transaksi')),
                
                TextColumn::make('plat_nomor')
                    ->label('Plat Nomor')
                    ->searchable(),
                
                TextColumn::make('waktu_masuk')
                    ->label('Waktu Masuk')
                    ->dateTime('d F Y H:i')
                    ->sortable(),
                
                TextColumn::make('waktu_keluar')
                    ->label('Waktu Keluar')
                    ->dateTime('d F Y H:i')
                    ->sortable(),
                
                TextColumn::make('durasi_jam')
                    ->label('Durasi (jam)')
                    ->sortable(),
                
                TextColumn::make('total_bayar')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->sortable()
                    ->summarize(Sum::make()->label('Total Pendapatan')->money('IDR')),
                
                TextColumn::make('pintuKeluar.user.name')
                    ->label('Petugas')
                    ->searchable(),
            ])
            ->filters([])
            ->actions([])
            ->bulkActions([]);
    }
}