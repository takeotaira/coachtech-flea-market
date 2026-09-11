@extends('layouts.auth')

@section('title', '会員登録')

@section('content')
    <div class="auth-container">
        <h1 class="auth-container__title">
            会員登録
        </h1>

        <form
            class="auth-form"
            action="{{ route('register') }}"
            method="POST"
            novalidate
        >
            @csrf

            <div class="auth-form__group">
                <label
                    class="auth-form__label"
                    for="name"
                >
                    ユーザー名
                </label>

                <input
                    class="auth-form__input"
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    autocomplete="name"
                >

                @error('name')
                    <p class="auth-form__error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

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
                    autocomplete="new-password"
                >

                @error('password')
                    <p class="auth-form__error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="auth-form__group">
                <label
                    class="auth-form__label"
                    for="password_confirmation"
                >
                    確認用パスワード
                </label>

                <input
                    class="auth-form__input"
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    autocomplete="new-password"
                >

                @error('password_confirmation')
                    <p class="auth-form__error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button
                class="auth-form__button"
                type="submit"
            >
                登録する
            </button>
        </form>

        <a
            class="auth-container__link"
            href="{{ route('login') }}"
        >
            ログインはこちら
        </a>
    </div>
@endsection