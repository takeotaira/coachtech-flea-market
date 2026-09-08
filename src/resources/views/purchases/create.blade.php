<h1>商品購入</h1>

<img
    src="{{ asset($item->image_path) }}"
    alt="{{ $item->name }}"
    width="200"
>

<p>商品名：{{ $item->name }}</p>

<p>価格：¥{{ number_format($item->price) }}</p>

<h2>配送先</h2>

@if ($sessionAddress)
    <p>〒{{ $sessionAddress['postal_code'] }}</p>
    <p>{{ $sessionAddress['address'] }}</p>

    @if ($sessionAddress['building'])
        <p>{{ $sessionAddress['building'] }}</p>
    @endif

@elseif ($profile)
    <p>〒{{ $profile->postal_code }}</p>
    <p>{{ $profile->address }}</p>

    @if ($profile->building)
        <p>{{ $profile->building }}</p>
    @endif

@else
    <p>配送先が登録されていません</p>
@endif

<a href="{{ route('purchases.address.edit', ['itemId' => $item->id]) }}">
    変更する
</a>

<form action="{{ route('purchases.store', ['itemId' => $item->id]) }}" method="POST">
    @csrf

    <div>
        <label for="payment_method">支払い方法</label>

        <select
            name="payment_method"
            id="payment_method"
        >
            <option value="">選択してください</option>
            <option value="コンビニ支払い"
                {{ old('payment_method') === 'コンビニ支払い' ? 'selected' : '' }}>
                コンビニ支払い
            </option>
            <option value="カード支払い"
                {{ old('payment_method') === 'カード支払い' ? 'selected' : '' }}>
                カード支払い
            </option>
        </select>

        @error('payment_method')
            <p>{{ $message }}</p>
        @enderror

        <p>
            支払い方法：
            <span id="selected-payment-method">未選択</span>
        </p>
    </div>

    <button type="submit">購入する</button>
</form>

    <script>
        const paymentSelect = document.getElementById('payment_method');
        const paymentDisplay = document.getElementById('selected-payment-method');

        paymentSelect.addEventListener('change', function () {
            paymentDisplay.textContent = this.value || '未選択';
        });
    </script>