<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


// Products
Route::prefix('products')->controller(ProductController::class)->name('products.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::delete('/delete/{id}', 'delete')->name('delete');
});


