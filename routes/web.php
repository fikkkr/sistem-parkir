<?php

use App\Http\Controllers\PintuKeluarController;
use App\Http\Controllers\PintuMasukController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('welcome');
});

Route::resource('/pintu-masuk', PintuMasukController::class);
Route::resource('/pintu-keluar', PintuKeluarController::class);
Route::get('/pintu-keluar/{pintuKeluar}/struk', [PintuKeluarController::class, 'struk'])->name('pintu-keluar.struk');
