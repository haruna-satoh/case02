<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;

class AdminController extends Controller
{
    public function adminLogin() {
        return view('auth.login', ['isAdmin' => true]);
    }

    public function login(LoginRequest $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)){
            return redirect()->return('admin.index');
        }

        return back()->withErrors([
            "login" => "ログイン情報が登録されていません",
        ])->withInput();
    }
}
