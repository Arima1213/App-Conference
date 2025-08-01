<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        // Tambahkan pengecualian CSRF di sini
        $middleware->validateCsrfTokens(except: [
            'midtrans-callback', // Tanpa slash di depan
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();