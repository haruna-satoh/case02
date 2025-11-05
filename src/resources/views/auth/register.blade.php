@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
    <div class="register__time-cade">
        <div class="register__title">
            <h1>会員登録</h1>
        </div>
        <form action="/register" method="post" class="form">
            @csrf
            <div class="form__group">
                <div class="form__group--title">
                    <span>名前</span>
                </div>
                <div class="form__group--content">
                    <div class="form__group--input">
                        <input type="name" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="form__error">
                        @error('name')
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
        </form>
    </div>
@endsection