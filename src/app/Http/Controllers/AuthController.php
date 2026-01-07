<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login', ['isAdmin' => false]);
    }

    public function login(LoginRequest $request) {
        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            if (is_null($user->email_verified_at)) {

                Auth::logout();

                return back()->withErrors([
                    'login' => 'メール認証が完了していないためログインできません。',
                ])->withInput();
            }

            return redirect('/attendance');
        }

        return back()->withErrors([
            'login' => 'ログイン情報が登録されていません',
        ])->withInput();
    }

    public function register() {
        return view('auth.register');
    }

    public function store(RegisterRequest $request) {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->sendEmailVerificationNotification();

        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}
