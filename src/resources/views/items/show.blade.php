<h1>{{ $item->name }}</h1>
<img
    src="{{ $item->image_path }}"
    alt="{{ $item->name }}"
    width="300"
>

<p>ブランド名：{{ $item->brand_name ?? 'なし' }}</p>

<p>価格：{{ $item->price }}円</p>

<p>いいね数：{{ $item->likes_count }}</p>

@auth
    @if ($isLiked)
        <form action="{{ route('likes.destroy', ['item_id' => $item->id]) }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit" class="like-button like-button--active">
                ★
            </button>
        </form>
    @else
        <form action="{{ route('likes.store', ['item_id' => $item->id]) }}" method="POST">
            @csrf

            <button type="submit" class="like-button">
                ☆
            </button>
        </form>
    @endif
@endauth

<div class="comment-count">
    <span class="comment-icon">💬</span>
    <span>{{ $item->comments_count }}</span>
</div>

@auth
    <form action="{{ route('comments.store', ['item_id' => $item->id]) }}" method="POST">
        @csrf

        <label for="content">商品へのコメント</label>

        <textarea
            id="content"
            name="content"
        >{{ old('content') }}</textarea>

        @error('content')
            <p>{{ $message }}</p>
        @enderror

        <button type="submit">コメントを送信する</button>
    </form>
@endauth

<h2>商品説明</h2>
<p>{{ $item->description }}</p>

<p>カテゴリー：</p>
@foreach ($item->categories as $category)
    <span>{{ $category->name }}</span>
@endforeach

@foreach ($item->comments as $comment)
    <p>
        {{ $comment->user->name }}：
        {{ $comment->content }}
    </p>
@endforeach

<p>
    商品状態：
    {{ $item->condition->name }}
</p>

@if ($item->purchase)
    <p>Sold</p>
@else
    @auth
        <a href="{{ route('purchases.create', ['item_id' => $item->id]) }}">
            購入手続きへ
        </a>
    @endauth
@endif

<style>
    .like-button {
        border: none;
        background: none;
        font-size: 32px;
        cursor: pointer;
        color: #777;
    }

    .like-button--active {
        color: #ff5555;
    }
</style>