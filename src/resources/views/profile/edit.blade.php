@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
@endsection

@section('content')
<div class="profile-container">
    <h1 class="profile-title">プロフィール設定</h1>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="profile-form">
        @csrf 
        @method('PUT')

        <div class="avatar-container">
            <img 
                id="avatarPreview" 
                src="{{ $user->profile && $user->profile->avatar ? asset('storage/' . $user->profile->avatar) : asset('images/default-avatar.png') }}" 
                alt="プロフィール画像" 
                class="avatar-preview">
            <label for="avatar" class="avatar-btn">画像を選択する</label>
            <input type="file" name="avatar" id="avatar" class="avatar-input" accept="image/*">
            @error('avatar')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="postcode">郵便番号</label>
            <input 
                type="text" 
                name="postcode" 
                id="postcode" 
                value="{{ old('postcode', $user->profile->postcode ?? '') }}">
            @error('postal_code')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input 
                type="text" 
                name="address" 
                id="address"
                value="{{ old('address', $user->profile->address ?? '') }}">
            @error('address')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input 
                type="text" 
                name="building" 
                id="building" 
                value="{{ old('building', $user->profile->building ?? '') }}">
            @error('building')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="update-btn">
            更新する
        </button>
    </form>
</div>
@endsection
