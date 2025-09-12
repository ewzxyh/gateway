<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            \App\Http\Middleware\AtualizarSaldosClientes::class,
        ]);
        $middleware->validateCsrfTokens([
             '/cashtime/*',
             '/mercadopago/*',
             '/efi/*',
             '/pagarme/*',
             '/xgate/*',
             '/witetec/*',
        ]);

        $middleware->alias([
            'check.token.secret' => \App\Http\Middleware\CheckTokenAndSecret::class,
            'check.admin' => \App\Http\Middleware\AdminMiddleware::class,
            'check.auth' => \App\Http\Middleware\AuthMiddleware::class,
            'security' => \App\Http\Middleware\SecurityMiddleware::class,
        ]);
        
        // Aplicar middleware de segurança globalmente
        $middleware->append([
            \App\Http\Middleware\SecurityMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
