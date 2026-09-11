@extends('layouts.app')

@section('title', '商品の出品')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')
    <main class="sell">
        <div class="sell__inner">
            <h1 class="sell__title">商品の出品</h1>

            <form
                class="sell-form"
                action="{{ route('items.store') }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf

                <div class="sell-form__group">
                    <label class="sell-form__label" for="image">
                        商品画像
                    </label>

                    <div class="image-upload">
                        <label class="image-upload__button" for="image">
                            画像を選択する
                        </label>

                        <input
                            class="image-upload__input"
                            type="file"
                            id="image"
                            name="image"
                            accept=".jpg,.jpeg,.png"
                        >
                    </div>

                    @error('image')
                        <p class="sell-form__error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <section class="sell-section">
                    <h2 class="sell-section__title">商品の詳細</h2>

                    <div class="sell-form__group">
                        <p class="sell-form__label">カテゴリー</p>

                        <div class="category-list">
                            @foreach ($categories as $category)
                                <label class="category">
                                    <input
                                        class="category__input"
                                        type="checkbox"
                                        name="categories[]"
                                        value="{{ $category->id }}"
                                        {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                                    >

                                    <span class="category__label">
                                        {{ $category->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        @error('categories')
                            <p class="sell-form__error">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="sell-form__group">
                        <label class="sell-form__label" for="condition_id">
                            商品の状態
                        </label>

                        <select
                            class="sell-form__select"
                            id="condition_id"
                            name="condition_id"
                        >
                            <option value="">選択してください</option>

                            @foreach ($conditions as $condition)
                                <option
                                    value="{{ $condition->id }}"
                                    {{ old('condition_id') == $condition->id ? 'selected' : '' }}
                                >
                                    {{ $condition->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('condition_id')
                            <p class="sell-form__error">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </section>

                <section class="sell-section">
                    <h2 class="sell-section__title">
                        商品名と説明
                    </h2>

                    <div class="sell-form__group">
                        <label class="sell-form__label" for="name">
                            商品名
                        </label>

                        <input
                            class="sell-form__input"
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                        >

                        @error('name')
                            <p class="sell-form__error">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="sell-form__group">
                        <label class="sell-form__label" for="brand_name">
                            ブランド名
                        </label>

                        <input
                            class="sell-form__input"
                            type="text"
                            id="brand_name"
                            name="brand_name"
                            value="{{ old('brand_name') }}"
                        >

                        @error('brand_name')
                            <p class="sell-form__error">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="sell-form__group">
                        <label class="sell-form__label" for="description">
                            商品の説明
                        </label>

                        <textarea
                            class="sell-form__textarea"
                            id="description"
                            name="description"
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <p class="sell-form__error">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="sell-form__group">
                        <label class="sell-form__label" for="price">
                            販売価格
                        </label>

                        <div class="price-input">
                            <span class="price-input__currency">¥</span>

                            <input
                                class="sell-form__input price-input__field"
                                type="number"
                                id="price"
                                name="price"
                                value="{{ old('price') }}"
                                min="0"
                            >
                        </div>

                        @error('price')
                            <p class="sell-form__error">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </section>

                <button class="sell-form__button" type="submit">
                    出品する
                </button>
            </form>
        </div>
    </main>
@endsection