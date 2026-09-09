<header>
    <form action="{{ route('items.index') }}" method="GET">
        @if (request('tab') === 'mylist')
            <input type="hidden" name="tab" value="mylist">
        @endif

        <input
            type="text"
            name="keyword"
            value="{{ request('keyword') }}"
            placeholder="なにをお探しですか？"
        >

        <button type="submit">
            検索
        </button>
    </form>

    @auth
        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit">
                ログアウト
            </button>
        </form>
    @else
        <a href="{{ route('login') }}">
            ログイン
        </a>
    @endauth
</header>

<div>
    <a href="{{ route('items.index', [
        'keyword' => request('keyword')
    ]) }}">
        おすすめ
    </a>

    <a href="{{ route('items.index', [
        'tab' => 'mylist',
        'keyword' => request('keyword')
    ]) }}">
        マイリスト
    </a>
</div>

<div>
    @foreach ($items as $item)
        <div>
            <a href="{{ route('items.show', ['itemId' => $item->id]) }}">
                <img
                    src="{{ asset($item->image_path) }}"
                    alt="{{ $item->name }}"
                    width="200"
                >

                <p>{{ $item->name }}</p>

                @if ($item->purchase)
                    <p>Sold</p>
                @endif
            </a>
        </div>
    @endforeach
</div>