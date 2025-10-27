@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login-container">
    <h1 class="login-title">ログイン</h1>

    <form method="POST" action="{{ route('login.post') }}" class="login-form">
        @csrf

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}">
            @error('email')
                {{ $message }}
            @enderror
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="password" id="password">
            @error('password')
                {{ $message }}
            @enderror
        </div>

        <button type="submit" class="login-btn">
            ログインする
        </button>

        <p class="register-link">
            <a href="/register">会員登録はこちら</a>
        </p>
    </form>
</div>
@endsection
