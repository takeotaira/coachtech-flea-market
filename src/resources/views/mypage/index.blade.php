@extends('layouts.app')

@section('title', 'マイページ')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')
    <main class="mypage">
        <section class="profile">
            <div class="profile-user">
                @if ($user->profile && $user->profile->profile_image)
                    <img
                        class="profile-image"
                        src="{{ asset($user->profile->profile_image) }}"
                        alt="{{ $user->name }}"
                    >
                @else
                    <span class="profile-image-placeholder"></span>
                @endif

                <h1 class="profile-name">
                    {{ $user->name }}
                </h1>
            </div>

            <a
                class="profile-edit-link"
                href="{{ route('mypage.profile.edit') }}"
            >
                プロフィールを編集
            </a>
        </section>

        <nav class="mypage-tabs">
            <a
                class="mypage-tabs-link
                    {{ request('page', 'sell') === 'sell' ? 'mypage-tabs-link-active' : '' }}"
                href="{{ route('mypage', ['page' => 'sell']) }}"
            >
                出品した商品
            </a>

            <a
                class="mypage-tabs-link
                    {{ request('page') === 'buy' ? 'mypage-tabs-link-active' : '' }}"
                href="{{ route('mypage', ['page' => 'buy']) }}"
            >
                購入した商品
            </a>
        </nav>

        <section class="mypage-items">
            @foreach ($items as $item)
                <article class="item-card">
                    <a
                        class="item-card-link"
                        href="{{ route('items.show', ['itemId' => $item->id]) }}"
                    >
                        <div class="item-card-image-wrapper">
                            <img
                                class="item-card-image"
                                src="{{ asset($item->image_path) }}"
                                alt="{{ $item->name }}"
                            >

                            @if ($item->purchase)
                                <span class="item-card-sold">
                                    Sold
                                </span>
                            @endif
                        </div>

                        <p class="item-card-name">
                            {{ $item->name }}
                        </p>
                    </a>
                </article>
            @endforeach
        </section>
    </main>
@endsection