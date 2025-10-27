@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">

<div class="purchase-container">
    <div class="purchase-left">
        <div class="product-image">
            @if($item->img_path)
                <img src="{{ asset('storage/' . $item->img_path) }}" alt="{{ $item->name }}">
            @else 
                <p>商品画像なし</p>
            @endif
        </div>
    </div>

    <div class="purchase-right">
        <h1 class="product-title">{{ $item->name }}</h1>
        <p class="brand-name">{{ $item->brand ?? 'ブランド名不明' }}</p>
        <p class="price">¥{{ number_format($item->price) }} <span class="tax">(税込)</span></p>

        <button id="favorite-button"
            class="favorite-button"
            data-liked="{{ $item->likes->contains('user_id', auth()->id()) ? 'true' : 'false' }}">
            ★ <span id="favorite-count">{{ $item->likes->count() }}</span>
        </button>
        <span class="comments">💬 {{ $item->comments->count() }}</span>

<script>
document.getElementById('favorite-button').addEventListener('click', function() {
    fetch("{{ route('items.show', ['item_id' => $item->id]) }}?favorite=1", {
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(response => {
        if (response.status === 403) {
            alert('ログインが必要です');
            return null;
        }
        return response.json();
    })
    .then(data => {
        if (!data) return;
        
        document.getElementById('favorite-count').textContent = data.likes_count;

        
        const btn = document.getElementById('favorite-button');
        btn.dataset.liked = data.liked ? 'true' : 'false';
        btn.style.color = data.liked ? 'gold' : 'black';
    })
    .catch(err => console.error(err));
});
</script>


        <form action="/purchase/{{ $item->id }}" method="GET">
            <button type="submit" class="purchase-button">購入手続きへ</button>
        </form>
        <div class="product-description">
            <h2>商品説明</h2>
            <p>{{ $item->description ?? '商品の説明はありません。' }}</p>
        </div>

        <div class="product-info">
            <h2>商品の情報</h2>
            <p>
                <span class="info-label">カテゴリー:</span>
                @if($item->categories && $item->categories->isNotEmpty())
                    @foreach($item->categories as $category)
                        <span class="category-tag">{{ $category->category }}</span>
                    @endforeach
                @else
                    <span class="category-tag">未設定</span>
                @endif
            </p>
            <p><span class="info-label">商品の状態:</span> {{ $item->condition->condition ?? '不明' }}</p>
        </div>

        <div class="comments-section">
            <h2>コメント {{ $item->comments ? $item->comments->count() : 0 }}</h2>

            @if($item->comments && $item->comments->isNotEmpty())
                @foreach($item->comments as $comment)
                    <div class="comment">
                        <div class="comment-user">
                            <div class="avatar"></div>
                            <span class="username">{{ $comment->user->name ?? 'ユーザー' }}</span>
                        </div>
                        <p class="comment-text">{{ $comment->comment }}</p>
                    </div>
                @endforeach
            @else
                <p>まだコメントはありません</p>
            @endif

            <form class="comment-form" action="{{ route('comments.store', $item->id) }}" method="POST">
                @csrf 
                
                @if ($errors->any())
                    <div class="error-messages" style="color: red; margin-bottom: 10px;">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <textarea name="comment" placeholder="商品のコメントを入力" required></textarea>
                <button type="submit" class="comment-button">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection


