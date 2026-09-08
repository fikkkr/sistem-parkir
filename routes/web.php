<?php

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PintuKeluarController;
use App\Http\Controllers\PintuMasukController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('welcome');
});

Route::resource('/pintu-masuk', PintuMasukController::class);
Route::resource('/pintu-keluar', PintuKeluarController::class);
Route::get('/pintu-keluar/{pintuKeluar}/struk', [PintuKeluarController::class, 'struk'])->name('pintu-keluar.struk');

// Laporan routes
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::post('/laporan/generate', [LaporanController::class, 'generateReport'])->name('laporan.generate');
Route::post('/laporan/export-pdf', [LaporanController::class, 'exportPDF'])->name('laporan.export-pdf');

// Filament laporan page route
Route::get('/filament/laporan', \App\Filament\Pages\LaporanPage::class);
