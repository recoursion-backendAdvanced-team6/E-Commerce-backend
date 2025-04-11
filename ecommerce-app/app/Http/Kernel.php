<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

// デフォルトのカーネルを継承して、作成したAuthenticateを追加
class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
    ];
}

