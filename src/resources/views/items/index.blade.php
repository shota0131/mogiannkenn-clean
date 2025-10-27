@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="items-container">

    <div class="tab-menu">
        <a href="{{ route('items.index', ['tab' => 'all']) }}"
        class="{{ $tab === 'all' ? 'active' : '' }}">
            おすすめ
        </a>
        <a href="{{ route('items.index', ['tab' => 'mylist']) }}"
        class="{{ $tab === 'mylist' ? 'active' : '' }}">
            マイリスト
        </a>
    </div>

    <div class="item-list">
        @forelse($items as $item)
            <div class="item-card">
                <a href="{{ route('items.show', $item->id) }}">
                    <div class="item-image">
                        @if($item->img_path)
                        <img src="{{ asset('storage/' . $item->img_path) }}" alt="{{ $item->name }}">
                        @else 
                            <div class="no-image">商品画像</div>
                        @endif
                        
                        @if($item->isSold())
                            <div class="sold-badge">SOLD</div>
                        @endif
                    </div>
                    <p class="item-name">{{ $item->name }}</p>
                </a>
            </div>
        @empty 
            <p class="no-items">商品がありません。</p>
        @endforelse
    </div>
</div>
@endsection
