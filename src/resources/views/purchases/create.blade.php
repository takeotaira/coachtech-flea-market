@extends('layouts.app')

@section('title', '商品購入')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/create.css') }}">
@endsection

@section('content')
    <main class="purchase">
        <form
            class="purchase-form"
            action="{{ route('purchases.store', ['itemId' => $item->id]) }}"
            method="POST"
        >
            @csrf

            <div class="purchase-main">
                <section class="purchase-item">
                    <img
                        class="purchase-item-image"
                        src="{{ asset($item->image_path) }}"
                        alt="{{ $item->name }}"
                    >

                    <div class="purchase-item-information">
                        <h1 class="purchase-item-name">
                            {{ $item->name }}
                        </h1>

                        <p class="purchase-item-price">
                            ¥{{ number_format($item->price) }}
                        </p>
                    </div>
                </section>

                <section class="purchase-section">
                    <h2 class="purchase-section-title">
                        支払い方法
                    </h2>

                    <div class="purchase-section-content">
                        <select
                            class="purchase-section-select"
                            id="payment_method"
                            name="payment_method"
                        >
                            <option value="">選択してください</option>

                            <option
                                value="コンビニ支払い"
                                {{ old('payment_method') === 'コンビニ支払い' ? 'selected' : '' }}
                            >
                                コンビニ支払い
                            </option>

                            <option
                                value="カード支払い"
                                {{ old('payment_method') === 'カード支払い' ? 'selected' : '' }}
                            >
                                カード支払い
                            </option>
                        </select>

                        @error('payment_method')
                            <p class="purchase-section-error">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </section>

                <section class="purchase-section">
                    <div class="purchase-section-heading">
                        <h2 class="purchase-section-title">
                            配送先
                        </h2>

                        <a
                            class="purchase-section-change-link"
                            href="{{ route('purchases.address.edit', ['itemId' => $item->id]) }}"
                        >
                            変更する
                        </a>
                    </div>

                    <div class="purchase-section-address">
                        @if ($sessionAddress)
                            <p>〒 {{ $sessionAddress['postal_code'] }}</p>
                            <p>{{ $sessionAddress['address'] }}</p>

                            @if ($sessionAddress['building'])
                                <p>{{ $sessionAddress['building'] }}</p>
                            @endif
                        @elseif ($profile)
                            <p>〒 {{ $profile->postal_code }}</p>
                            <p>{{ $profile->address }}</p>

                            @if ($profile->building)
                                <p>{{ $profile->building }}</p>
                            @endif
                        @else
                            <p>配送先が登録されていません</p>
                        @endif
                    </div>
                </section>
            </div>

            <aside class="purchase-sidebar">
                <dl class="purchase-summary">
                    <div class="purchase-summary-row">
                        <dt>商品代金</dt>
                        <dd>¥{{ number_format($item->price) }}</dd>
                    </div>

                    <div class="purchase-summary-row">
                        <dt>支払い方法</dt>
                        <dd id="selected-payment-method">
                            {{ old('payment_method') ?: '未選択' }}
                        </dd>
                    </div>
                </dl>

                <button class="purchase-button" type="submit">
                    購入する
                </button>
            </aside>
        </form>
    </main>

    <script>
        const paymentSelect = document.getElementById('payment_method');
        const paymentDisplay = document.getElementById('selected-payment-method');

        paymentSelect.addEventListener('change', function () {
            paymentDisplay.textContent = this.value || '未選択';
        });
    </script>
@endsection