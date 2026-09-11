@extends('layouts.app')

@section('title', '住所の変更')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/address.css') }}">
@endsection

@section('content')
    <main class="address">
        <div class="address__inner">
            <h1 class="address__title">住所の変更</h1>

            <form
                class="address-form"
                action="{{ route('purchases.address.update', ['itemId' => $item->id]) }}"
                method="POST"
            >
                @csrf

                <div class="address-form__group">
                    <label class="address-form__label" for="postal_code">
                        郵便番号
                    </label>

                    <input
                        class="address-form__input"
                        type="text"
                        id="postal_code"
                        name="postal_code"
                        value="{{ old('postal_code') }}"
                    >

                    @error('postal_code')
                        <p class="address-form__error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="address-form__group">
                    <label class="address-form__label" for="address">
                        住所
                    </label>

                    <input
                        class="address-form__input"
                        type="text"
                        id="address"
                        name="address"
                        value="{{ old('address') }}"
                    >

                    @error('address')
                        <p class="address-form__error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="address-form__group">
                    <label class="address-form__label" for="building">
                        建物名
                    </label>

                    <input
                        class="address-form__input"
                        type="text"
                        id="building"
                        name="building"
                        value="{{ old('building') }}"
                    >

                    @error('building')
                        <p class="address-form__error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button class="address-form__button" type="submit">
                    更新する
                </button>
            </form>
        </div>
    </main>
@endsection