<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CallbackController;

Route::post('pagarme/webhook', [CallbackController::class, 'webhookPagarme']);
