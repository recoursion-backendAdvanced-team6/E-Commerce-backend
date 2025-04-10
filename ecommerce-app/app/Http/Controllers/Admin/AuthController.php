<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use PDO;

class AuthController extends Controller
{

    public function loginForm(){
        return view('admin.login');
    }

    public function registerForm(){
        return view('admin.register');
    }

    public function register(Request $request){
        $request->validate([
            'name' => ['string'],
            'email'=> ['required', 'email', 'unique:admins,email'],
            'password'=>['required', 'min:6', 'confirmed'],
            'role'=> ['nullable','in:superadmin,editor,viewer'],
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::guard('admin')->login($admin); // 自動ログイン（任意）

        return redirect()->route('admin.dashboard');
    }


    public function login(Request $request){
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6']
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
