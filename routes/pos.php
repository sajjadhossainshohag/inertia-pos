<?php

use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;


Route::prefix('pos')->controller(PosController::class)->name('pos.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/orders', 'orders')->name('orders');
    Route::post('/place-order', 'placeOrder')->name('place.order');
});