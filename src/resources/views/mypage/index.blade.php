<h1>マイページ</h1>

<div>
    @if ($user->profile && $user->profile->profile_image)
        <img
            src="{{ asset($user->profile->profile_image) }}"
            alt="プロフィール画像"
            width="100"
        >
    @endif

    <p>{{ $user->name }}</p>

    <a href="{{ route('mypage.profile.edit') }}">
        プロフィールを編集
    </a>
</div>

<div>
    <a href="{{ route('mypage', ['page' => 'sell']) }}">
        出品した商品
    </a>

    <a href="{{ route('mypage', ['page' => 'buy']) }}">
        購入した商品
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