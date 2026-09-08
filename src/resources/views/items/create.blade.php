<h1>商品の出品</h1>

<form
    action="{{ route('items.store') }}"
    method="POST"
    enctype="multipart/form-data"
    novalidate
>
    @csrf

    <div>
        <label>商品画像</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png">

        @error('image')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <div>
        <p>カテゴリー</p>

        @foreach ($categories as $category)
            <label>
                <input
                    type="checkbox"
                    name="categories[]"
                    value="{{ $category->id }}"
                    {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                >
                {{ $category->name }}
            </label>
        @endforeach

        @error('categories')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label>商品の状態</label>

        <select name="condition_id">
            <option value="">選択してください</option>

            @foreach ($conditions as $condition)
                <option
                    value="{{ $condition->id }}"
                    {{ old('condition_id') == $condition->id ? 'selected' : '' }}
                >
                    {{ $condition->name }}
                </option>
            @endforeach
        </select>

        @error('condition_id')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label>商品名</label>
        <input
            type="text"
            name="name"
            value="{{ old('name') }}"
        >

        @error('name')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label>ブランド名</label>
        <input
            type="text"
            name="brand_name"
            value="{{ old('brand_name') }}"
        >

        @error('brand_name')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label>商品の説明</label>
        <textarea name="description">{{ old('description') }}</textarea>

        @error('description')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label>販売価格</label>
        <input
            type="number"
            name="price"
            value="{{ old('price') }}"
            min="0"
        >

        @error('price')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <button type="submit">
        出品する
    </button>
</form>