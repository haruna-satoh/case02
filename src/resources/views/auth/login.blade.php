@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
    <div class="login__time-cade">
        <div class="login__title">
            <h1>{{ $isAdmin ? '管理者ログイン' : 'ログイン' }}</h1>
        </div>
        <form action="{{ $isAdmin ? route('admin.login') : route('login') }}" method="post">
            @csrf
            <div class="form__group">
                <div class="form__group--content">
                    <div class="form__error">
                        @error('login')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="form__group--title">
                    <span>メールアドレス</span>
                </div>
                <div class="form__group--content">
                    <div class="form__group--input">
                        <input type="email" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="form__error">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="form__group--title">
                    <span>パスワード</span>
                </div>
                <div class="form__group--content">
                    <div class="form__group--input">
                        <input type="password" name="password">
                    </div>
                    <div class="form__error">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form__button">
                <button type="submit">
                    {{ $isAdmin ? '管理者ログインする' : 'ログインする' }}
                </button>
            </div>
            <div class="form__button--register">
                @unless ($isAdmin)
                    <a href="{{ route('register') }}">
                        新規登録はこちら
                    </a>
                @endunless
            </div>
        </form>
    </div>
@endsection