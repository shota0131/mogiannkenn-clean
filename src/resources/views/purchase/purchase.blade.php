@extends('layouts.app')

@section('title', '購入画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">

    <div class="purchase-left">
        <div class="product-info">
            <div class="product-image">
                @if($item->img_path)
                    <img src="{{ asset('storage/' . $item->img_path) }}" alt="{{ $item->name }}">
                @else
                    <div class="no-image">商品画像</div>
                @endif
            </div>
            <div class="product-detail">
                <h2 class="product-name">{{ $item->name }}</h2>
                <p class="product-price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>

        <hr>

        <div class="payment-method">
            <label for="payment">支払い方法</label>
            <select id="payment" name="payment" required>
                <option value="">選択してください</option>
                <option value="convenience-store" {{ old('payment_method') === 'convenience-store' ? 'selected' : '' }}>コンビニ支払い</option>
                <option value="credit_card" {{ old('payment_method') === 'credit_card' ? 'selected' : '' }}>カード支払い</option>
            </select>
            @error('payment_method')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <hr>

        <div class="shipping-address">
            <div class="address-header">
                <span>配送先</span>
                <a href="{{ route('address.edit', ['item_id' => $item->id]) }}" class="change-link">変更する</a>
            </div>

            @php
                $profile = $user->profile;
            @endphp

            <p>〒{{ old('postal_code', $profile->postcode ?? 'XXX-YYYY') }}</p>
            <p>{{ old('address', $profile->address ?? 'ここには住所と建物が入ります') }}</p>
            <p>{{ old('building', $profile->building ?? '') }}</p>

            @error('sending_address')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <hr>
    </div>

    <div class="purchase-right">
        <div class="summary-box">
            <div class="summary-row">
                <span>商品代金</span>
                <span>¥{{ number_format($item->price) }}</span>
            </div>
            <div class="summary-row">
                <span>支払い方法</span>
                <span id="selected-payment">未選択</span>
            </div>
        </div>

        <form method="POST" action="{{ route('purchase.store', $item->id) }}">
            @csrf
            <input type="hidden" name="payment_method" id="payment_method" value="{{ old('payment_method') }}">
            <input type="hidden" name="sending_postcode" value="{{ old('postal_code', $profile->postcode ?? '') }}">
            <input type="hidden" name="sending_address" value="{{ old('address', $profile->address ?? '') }}">
            <input type="hidden" name="sending_building" value="{{ old('building', $profile->building ?? '') }}">
            <button type="submit" class="purchase-btn">購入する</button>
        </form>

        <script>
        document.getElementById('payment').addEventListener('change', function() {
            document.getElementById('payment_method').value = this.value;
            document.getElementById('selected-payment').textContent = this.options[this.selectedIndex].text;
        });
        </script>

    </div>
</div>
@endsection
