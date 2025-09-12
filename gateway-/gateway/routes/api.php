<?php

use App\Http\Controllers\Api\BilletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SaqueController;
use App\Http\Controllers\Api\DepositController;
use Illuminate\Support\Facades\Artisan;

Route::get('/link-storage', function (Request $request) {
    $action = $request->get('action');
    if($action == 'migrate'){
        Artisan::call('migrate');
    } elseif($action == 'storage'){
        Artisan::call('storage:unlink');
        Artisan::call('storage:link');
    }
    // Recomendado: só permitir em ambiente local ou com autenticação
    //if (app()->environment('local')) {
        return redirect('/');
    //}

   // abort(403, 'Acesso não autorizado.');
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/* PIX */
// Rota temporária - intercepta GET indevidos até service worker ser atualizado
Route::get('wallet/deposit/payment', function(Request $request) {
    return response()->json([
        'error' => 'Método GET não permitido',
        'message' => 'Esta rota aceita apenas POST. Limpe o cache do navegador (Ctrl+Shift+R).',
        'correct_method' => 'POST'
    ], 405);
});
Route::middleware('check.token.secret')->post('wallet/deposit/payment', [DepositController::class, 'makeDeposit']);
Route::middleware('check.token.secret')->post('pixout', [SaqueController::class, 'makePayment']);
Route::post('status', [DepositController::class, 'statusDeposito']);

/* BOLETO */
Route::middleware('check.token.secret')->post('billet/charge', [BilletController::class, 'charge']);