@extends('layouts.app')

@section('title', 'メール認証')

@section('content')
<div class="verify-container">
    <p class="verify-massage">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    <a href="http://localhost:8025" target="_blank" class="verify-button">
        認証はこちらから
    </a>

    @if(session('status') == 'verification-link-sent')
        <p class="resend-success">新しい認証メールを送信しました。</P>
    @endif 

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="resend-link">
            認証メールを再送する
        </button>
    </form>
</div>
@endsection