<?php

use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\Telegram\ConnectController;
use App\Http\Controllers\Telegram\StatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['api' => 'fltg', 'version' => '1'];
});

Route::prefix('shops/{shop}')->whereNumber('shop')->group(function () {
    Route::post('telegram/connect', ConnectController::class)->name('shops.telegram.connect');
    Route::get('telegram/status', StatusController::class)->name('shops.telegram.status');
    Route::post('orders', [OrderController::class, 'store'])->name('shops.orders.store');
});
