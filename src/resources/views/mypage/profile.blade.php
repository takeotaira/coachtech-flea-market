@extends('layouts.app')

@section('title', 'プロフィール設定')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage/profile.css') }}">
@endsection

@section('content')
    <main class="profile-setting">
        <div class="profile-setting__inner">
            <h1 class="profile-setting__title">
                プロフィール設定
            </h1>

            <form
                class="profile-form"
                action="{{ route('mypage.profile.update') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PATCH')

                <div class="profile-form__image-group">
                    @if ($user->profile && $user->profile->profile_image)
                        <img
                            class="profile-form__image"
                            src="{{ asset($user->profile->profile_image) }}"
                            alt="{{ $user->name }}"
                        >
                    @else
                        <span class="profile-form__image-placeholder"></span>
                    @endif

                    <label
                        class="profile-form__image-button"
                        for="profile_image"
                    >
                        画像を選択する
                    </label>

                    <input
                        class="profile-form__file-input"
                        type="file"
                        id="profile_image"
                        name="profile_image"
                        accept=".jpg,.jpeg,.png"
                    >
                </div>

                @error('profile_image')
                    <p class="profile-form__error">
                        {{ $message }}
                    </p>
                @enderror

                <div class="profile-form__group">
                    <label class="profile-form__label" for="name">
                        ユーザー名
                    </label>

                    <input
                        class="profile-form__input"
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                    >

                    @error('name')
                        <p class="profile-form__error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="profile-form__group">
                    <label class="profile-form__label" for="postal_code">
                        郵便番号
                    </label>

                    <input
                        class="profile-form__input"
                        type="text"
                        id="postal_code"
                        name="postal_code"
                        value="{{ old('postal_code', $user->profile?->postal_code) }}"
                    >

                    @error('postal_code')
                        <p class="profile-form__error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="profile-form__group">
                    <label class="profile-form__label" for="address">
                        住所
                    </label>

                    <input
                        class="profile-form__input"
                        type="text"
                        id="address"
                        name="address"
                        value="{{ old('address', $user->profile?->address) }}"
                    >

                    @error('address')
                        <p class="profile-form__error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="profile-form__group">
                    <label class="profile-form__label" for="building">
                        建物名
                    </label>

                    <input
                        class="profile-form__input"
                        type="text"
                        id="building"
                        name="building"
                        value="{{ old('building', $user->profile?->building) }}"
                    >

                    @error('building')
                        <p class="profile-form__error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button class="profile-form__button" type="submit">
                    更新する
                </button>
            </form>
        </div>
    </main>
@endsection