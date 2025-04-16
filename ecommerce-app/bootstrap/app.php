<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

//return Application::configure(basePath: dirname(__DIR__))   
$app = Application::configure(basePath: dirname(__DIR__))   
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Auth:adminのためにデフォルトから作成したAuthenticateに変える
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class, 
        ]);

        // CSRFを無功にしたいルートの設定
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
            'stripe/webhook/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

    
return $app;
