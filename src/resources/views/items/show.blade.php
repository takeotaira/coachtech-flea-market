<h1>{{ $item->name }}</h1>

<p>価格：{{ $item->price }}円</p>

<p>
    商品状態：
    {{ $item->condition->name }}
</p>

<p>いいね数：{{ $item->likes_count }}</p>

@auth
    @if ($isLiked)
        <form action="{{ route('likes.destroy', ['item_id' => $item->id]) }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit">
                いいね解除
            </button>
        </form>
    @else
        <form action="{{ route('likes.store', ['item_id' => $item->id]) }}" method="POST">
            @csrf

            <button type="submit">
                いいねする
            </button>
        </form>
    @endif
@endauth

<p>コメント数：{{ $item->comments_count }}</p>

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

@foreach ($item->categories as $category)
    <span>{{ $category->name }}</span>
@endforeach

@foreach ($item->comments as $comment)
    <p>
        {{ $comment->user->name }}：
        {{ $comment->content }}
    </p>
@endforeach

@if ($item->purchase)
    <p>Sold</p>
@else
    @auth
        <a href="{{ route('purchases.create', ['item_id' => $item->id]) }}">
            購入手続きへ
        </a>
    @endauth
@endif