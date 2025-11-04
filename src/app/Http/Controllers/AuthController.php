<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function login() {
        return view('auth.login', ['isAdmin' => false]);
    }

    public function register() {
        return view('auth.register');
    }
}
