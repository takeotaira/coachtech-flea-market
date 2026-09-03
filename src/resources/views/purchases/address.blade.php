<h1>住所の変更</h1>

<form action="{{ route('purchases.address.update', ['item_id' => $item->id]) }}" method="POST">
    @csrf

    <div>
        <label for="postal_code">郵便番号</label>
        <input
            type="text"
            id="postal_code"
            name="postal_code"
            value="{{ old('postal_code') }}"
        >

        @error('postal_code')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="address">住所</label>
        <input
            type="text"
            id="address"
            name="address"
            value="{{ old('address') }}"
        >

        @error('address')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="building">建物名</label>
        <input
            type="text"
            id="building"
            name="building"
            value="{{ old('building') }}"
        >
    </div>

    <button type="submit">更新する</button>
</form>