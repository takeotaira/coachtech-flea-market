@extends('layouts.app')

@section('title', $item->name)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
    <main class="item-detail">
        <div class="item-detail-image-area">
            <img
                class="item-detail-image"
                src="{{ asset($item->image_path) }}"
                alt="{{ $item->name }}"
            >
        </div>

        <div class="item-detail-content">
            <section class="item-summary">
                <h1 class="item-summary-name">{{ $item->name }}</h1>

                <p class="item-summary-brand">
                    {{ $item->brand_name ?? 'ブランド名なし' }}
                </p>

                <p class="item-summary-price">
                    <span>¥</span>
                    {{ number_format($item->price) }}
                    <span class="item-summary-tax">（税込）</span>
                </p>

                <div class="item-summary-reactions">
                    <div class="reaction">
                        @auth
                            @if ($isLiked)
                                <form
                                    class="reaction-form"
                                    action="{{ route('likes.destroy', ['itemId' => $item->id]) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="reaction-button"
                                        type="submit"
                                        aria-label="いいねを解除する"
                                    >
                                        <img
                                            class="reaction-icon"
                                            src="{{ asset('images/heart-pink.png') }}"
                                            alt=""
                                        >
                                    </button>
                                </form>
                            @else
                                <form
                                    class="reaction-form"
                                    action="{{ route('likes.store', ['itemId' => $item->id]) }}"
                                    method="POST"
                                >
                                    @csrf

                                    <button
                                        class="reaction-button"
                                        type="submit"
                                        aria-label="いいねする"
                                    >
                                        <img
                                            class="reaction-icon"
                                            src="{{ asset('images/heart-default.png') }}"
                                            alt=""
                                        >
                                    </button>
                                </form>
                            @endif
                        @else
                            <img
                                class="reaction-icon"
                                src="{{ asset('images/heart-default.png') }}"
                                alt="いいね"
                            >
                        @endauth

                        <span class="reaction-count">
                            {{ $item->likes_count }}
                        </span>
                    </div>

                    <div class="reaction">
                        <img
                            class="reaction-icon"
                            src="{{ asset('images/comment.png') }}"
                            alt="コメント"
                        >

                        <span class="reaction-count">
                            {{ $item->comments_count }}
                        </span>
                    </div>
                </div>

                @if ($item->purchase)
                    <p class="purchase-button purchase-button-sold">
                        Sold
                    </p>
                @else
                    @auth
                        <a
                            class="purchase-button"
                            href="{{ route('purchases.create', ['itemId' => $item->id]) }}"
                        >
                            購入手続きへ
                        </a>
                    @endauth
                @endif
            </section>

            <section class="item-description">
                <h2 class="section-title">商品説明</h2>

                <p class="item-description-text">
                    {{ $item->description }}
                </p>
            </section>

            <section class="item-information">
                <h2 class="section-title">商品の情報</h2>

                <dl class="item-information-list">
                    <div class="item-information-row">
                        <dt>商品カテゴリー</dt>
                        <dd class="item-information-categories">
                            @foreach ($item->categories as $category)
                                <span class="category">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </dd>
                    </div>

                    <div class="item-information-row">
                        <dt>商品の状態</dt>
                        <dd>{{ $item->condition->name }}</dd>
                    </div>
                </dl>
            </section>

            <section class="comments">
                <h2 class="comments-title">
                    コメント（{{ $item->comments_count }}）
                </h2>

                @foreach ($item->comments as $comment)
                    <article class="comment">
                        <div class="comment-user">
                            @if ($comment->user->profile?->profile_image)
                                <img
                                    class="comment-profile-image"
                                    src="{{ asset($comment->user->profile->profile_image) }}"
                                    alt="{{ $comment->user->name }}"
                                >

                            @else
                                <span class="comment-profile-placeholder"></span>
                            @endif

                            <span class="comment-user-name">
                                {{ $comment->user->name }}
                            </span>
                        </div>

                        <p class="comment-content">
                            {{ $comment->content }}
                        </p>
                    </article>
                @endforeach

                @auth
                    <form
                        class="comment-form"
                        action="{{ route('comments.store', ['itemId' => $item->id]) }}"
                        method="POST"
                    >
                        @csrf

                        <label class="comment-form-label" for="content">
                            商品へのコメント
                        </label>

                        <textarea
                            class="comment-form-textarea"
                            id="content"
                            name="content"
                        >{{ old('content') }}</textarea>

                        @error('content')
                            <p class="comment-form-error">
                                {{ $message }}
                            </p>
                        @enderror

                        <button class="comment-form-button" type="submit">
                            コメントを送信する
                        </button>
                    </form>
                @endauth
            </section>
        </div>
    </main>
@endsection