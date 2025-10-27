@extends('layouts.app')

@section('title', 'マイページ')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="mypage-container">

    <div class="profile-header">
        <div class="profile-icon">
        @if($user->profile && $user->profile->avatar)
            <img src="{{ asset('storage/' . $user->profile->avatar) }}" alt="プロフィール画像">
        @else 
            <div class='no-icon'></div>
        @endif
        </div>
        <div class="profile-info">
            <h2 class="profile-name">{{ $user->name }}</h2>
            <a href="{{ route('profile.edit') }}" class="edit-profile-btn">プロフィールを編集</a>
        </div>
    </div>

    <div class="tab-menu">
        <a href="{{ route('profile.index', ['tab' => 'selling']) }}" class="{{ $tab === 'selling' ? 'active' : '' }}">
            出品した商品
        </a>
        <a href="{{ route('profile.index', ['tab' => 'purchased']) }}" class="{{ $tab === 'purchased' ? 'active' : '' }}">
            購入した商品
        </a>
    </div>

    <hr class="divider">

    <div class="product-list">
        @forelse($items as $item)
            <div class="product-card">
                <a href="{{route('items.show', $item->id) }}">
                    <div class="product-image">
                        @if($item->img_path)
                            <img src="{{ asset('storage/' . $item->img_path) }}" alt="{{ $item->name }}">
                        @else 
                            <div class="no-image">商品画像</div>
                        @endif
                    </div>
                    <p class="product-name">{{ $item->name }}</p>
                </a>
            </div>
        @empty
            <p class="no-items">表示できる商品がありません</p>
        @endforelse
    </div>
</div>
@endsection
