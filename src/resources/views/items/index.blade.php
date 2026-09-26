@extends('layouts.app')

@section('title', '商品一覧')

@section('css')
    <link
        rel="stylesheet"
        href="{{ asset('css/items/index.css') }}"
    >
@endsection

@section('content')
    <div class="item-list">
        <nav class="item-list-tabs">
            <a
                class="item-list-tab {{ request('tab') !== 'mylist' ? 'item-list-tab-active' : '' }}"
                href="{{ route('items.index', [
                    'keyword' => request('keyword'),
                ]) }}"
            >
                おすすめ
            </a>

            <a
                class="item-list-tab {{ request('tab') === 'mylist' ? 'item-list-tab-active' : '' }}"
                href="{{ route('items.index', [
                    'tab' => 'mylist',
                    'keyword' => request('keyword'),
                ]) }}"
            >
                マイリスト
            </a>
        </nav>

        <div class="item-list-grid">
            @foreach ($items as $item)
                <article class="item-card">
                    <a
                        class="item-card-link"
                        href="{{ route('items.show', [
                            'itemId' => $item->id,
                        ]) }}"
                    >
                        <div class="item-card-image-wrapper">
                            <img
                                class="item-card-image"
                                src="{{ asset($item->image_path) }}"
                                alt="{{ $item->name }}"
                            >

                            @if ($item->purchase)
                                <span class="item-card-sold">Sold</span>
                            @endif
                        </div>

                        <p class="item-card-name">
                            {{ $item->name }}
                        </p>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
@endsection