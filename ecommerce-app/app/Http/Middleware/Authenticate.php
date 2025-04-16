<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

// Authenticateを継承して、Adminルートを追加
class Authenticate extends Middleware
{
    protected function redirectTo(Request $request)
    {
        if (! $request->expectsJson()) {
            // adminルートなら管理者ログイン画面へ
            if ($request->is('admin/*')) {
                return route('admin.login'); 
            }

            // 通常のログイン画面へ
            return route('login');
        }
    }
}
