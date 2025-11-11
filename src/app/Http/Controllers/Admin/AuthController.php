<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;

class AuthController extends Controller
{
    public function adminLogin() {
        return view('auth.login', ['isAdmin' => true]);
    }

    public function login(LoginRequest $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)){
            return redirect()->route('admin.index');
        }

        return back()->withErrors([
            "login" => "ログイン情報が登録されていません",
        ])->withInput();
    }

    public function index() {
        return view('admin.index');
    }
}
