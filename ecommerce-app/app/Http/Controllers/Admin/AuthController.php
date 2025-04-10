<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;

class AuthController extends Controller
{

    public function loginForm(){
        return view('admin.login');
    }


    public function login(Request $request){
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['password', 'string', 'min:6']
        ]);

        $credentials = $request->only('email', 'password');

        if(Auth::Guard('admin')->attempt($credentials)){
            return redirect()->intended('admin/dashboard');
        }

        return back()->withErrors([
            'email'=> 'ログインに失敗しました',
        ]);
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
