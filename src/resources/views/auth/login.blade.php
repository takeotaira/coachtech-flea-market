@extends('layouts.auth')

@section('title', 'ログイン')

@section('content')
    <div class="auth-container">
        <h1 class="auth-container__title">
            ログイン
        </h1>

        <form
            class="auth-form"
            action="{{ route('login') }}"
            method="POST"
            novalidate
        >
            @csrf

            <div class="auth-form__group">
                <label
                    class="auth-form__label"
                    for="email"
                >
                    メールアドレス
                </label>

                <input
                    class="auth-form__input"
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                >

                @error('email')
                    <p class="auth-form__error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="auth-form__group">
                <label
                    class="auth-form__label"
                    for="password"
                >
                    パスワード
                </label>

                <input
                    class="auth-form__input"
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="current-password"
                >

                @error('password')
                    <p class="auth-form__error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button
                class="auth-form__button"
                type="submit"
            >
                ログイン
            </button>
        </form>

        <a
            class="auth-container__link"
            href="{{ route('register') }}"
        >
            会員登録はこちら
        </a>
    </div>
@endsection