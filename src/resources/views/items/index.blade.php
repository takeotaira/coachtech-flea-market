<h1>商品一覧</h1>

<div>
    <a href="{{ route('items.index', ['keyword' => request('keyword')]) }}">
        おすすめ
    </a>

    <a href="{{ route('items.index', [
        'tab' => 'mylist',
        'keyword' => request('keyword')
    ]) }}">
        マイリスト
    </a>

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

        <button type="submit">検索</button>
    </form>
</div>

<div>
    @foreach ($items as $item)
        <div>
            <a href="{{ route('items.show', ['item_id' => $item->id]) }}">
                <img
                    src="{{ $item->image_path }}"
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