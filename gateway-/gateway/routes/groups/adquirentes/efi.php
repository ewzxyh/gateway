<?php

use App\Http\Controllers\Api\BilletController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CallbackController;


Route::post('efi/callback', [CallbackController::class, 'callbackEfi']);
Route::any('efi/billet/notification', [BilletController::class, 'callbackCharge']);
Route::any('efi/card/notification', [CallbackController::class, 'callbackCard']);
