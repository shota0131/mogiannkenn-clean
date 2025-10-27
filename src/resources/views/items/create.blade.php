@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

@section('content')
<div class="create-container">
    <h1 class="create-title">商品の出品</h1>

    @if($errors->any())
        <div class="errors" style="color: red; margin-bottom: 20px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
        @csrf 

        <div class="form-group">
            <label for="image">商品画像</label>
            <input type="file" name="image" id="image" class="image-input">
            @error('image')
                {{ $message }}
            @enderror
        </div>

        <div class="form-group">
            <h3>商品の詳細</h3>
            <label>カテゴリー</label>
            <div class="category-list">
                @foreach($categories as $category)
                    <label class="category-item">
                        <input type="checkbox" name="category_id[]" value="{{ $category->id }}"
                            {{ is_array(old('category_id')) && in_array($category->id, old('category_id')) ? 'checked' : '' }}>
                        <span>{{ $category->category }}</span>
                    </label>
                @endforeach
            </div>
            @error('category_id')
                <div class="error-message" style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="condition">商品の状態</label>
            <select name="condition" id="condition">
                <option value="">選択してください</option>
                <option value="良好" {{ old('condition') == '良好' ? 'selected' : '' }}>良好</option>
                <option value="やや傷や汚れあり" {{ old('condition') == 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                <option value="状態が悪い" {{ old('condition') == '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
            </select>
            @error('condition')
                {{ $message }}
            @enderror
        </div>
        
        <div class="form-group">
            <label for="name">商品名</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}">
            @error('name')
                {{ $message }}
            @enderror
        </div>

        <div class="form-group">
            <label for="brand">ブランド名</label>
            <input type="text" name="brand" id="brand" value="{{ old( 'brand' )}}">
            @error('brand')
                {{ $message }}
            @enderror
        </div>

        <div class="form-group">
            <label for="description">商品の説明</label>
            <textarea name="description" id="description" rows="4">{{ old('description') }}</textarea>
            @error('description')
                {{ $message }}
            @enderror
        </div>

        <div class="form-group">
            <label for="price">販売価格</label>
            <input type="number" name="price" id="price" value="{{ old('price') }}">
            @error('price')
                {{ $message }}
            @enderror
        </div>

        <button type="submit" class="submit-btn">出品する</button>
    </form>
</div>
@endsection


