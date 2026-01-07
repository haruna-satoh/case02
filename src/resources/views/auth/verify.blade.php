@extends('layouts.guest')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/verify.css') }}">
@endsection

@section('content')
    <div class="verify-container">
        <div class="verify-card">
            <p class="verify-text">
                登録していただいたメールアドレスに認証メールを送付しました。<br>
                メール認証を完了してください。
            </p>
            <a href="http://localhost:8025" class="verify-button">
                認証はこちらから
            </a>

            <form action="{{ route('verification.resend') }}" method="post">
                @csrf
                <button class="resend-link">
                    認証メールを再送する
                </button>
            </form>

            @if(session('message'))
                <p class="verify-message">{{ session('message') }}</p>
            @endif
        </div>
    </div>
@endsection