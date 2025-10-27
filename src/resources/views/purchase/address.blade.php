@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">

<div class="address-container">
    <h1 class="address-title">住所の変更</h1>

    <form method="POST" action="{{ route('address.update', ['item_id' => $item->id]) }}" class="address-form">
        @csrf 
        @method('PUT')

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code ?? '') }}">

            @error('postal_code')
                    {{ $message }}
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $user->address ?? '') }}">

            @error('address')
                    {{ $message }}
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" id="building" name="building" value="{{ old('building', $user->building ?? '') }}">
        </div>

        <button type="submit" class="update-button">更新する</button>
    </form>
</div>
@endsection