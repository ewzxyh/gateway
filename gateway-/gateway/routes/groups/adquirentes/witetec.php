<?php

use App\Http\Controllers\Api\Adquirentes\WitetecController;
use Illuminate\Support\Facades\Route;

Route::post('witetec/callback/deposit', [WitetecController::class, 'callbackDeposit']);
Route::post('witetec/callback/withdraw', [WitetecController::class, 'callbackWithdraw']);