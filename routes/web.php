<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PintuMasukController;
use App\Http\Controllers\PintuKeluarController;

Route::get('/dashboard', function () {
    return view('welcome');
});

Route::resource('/pintu-masuk', PintuMasukController::class);
Route::resource('/pintu-keluar', PintuKeluarController::class);