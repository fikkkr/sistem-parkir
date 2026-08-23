<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use App\Models\PintuMasuk;
use App\Models\MasterTarif;
use App\Models\PintuKeluar;
use Filament\Widgets\StatsOverviewWidget as widget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Total Pintu Masuk', PintuMasuk::count())
                ->description('Jumlah total kendaraan masuk yang tercatat')
                ->descriptionIcon('heroicon-o-information-circle')
                ->color('success')
                ->icon('heroicon-o-truck'),
            Stat::make('Total Pintu Keluar', PintuKeluar::count())
                ->description('Jumlah total kendaraan keluar yang tercatat')
                ->descriptionIcon('heroicon-o-information-circle')
                ->color('danger')
                ->icon('heroicon-o-truck'),
            Stat::make('Total Master Tarif', MasterTarif::count())
                ->description('Jumlah total Uang yang tercatat')
                ->descriptionIcon('heroicon-o-information-circle')
                ->color('primary')
                ->icon('heroicon-o-currency-dollar'),
        ];
    }
}
