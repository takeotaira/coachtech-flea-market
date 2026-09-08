<h1>プロフィール設定</h1>

<form
    action="{{ route('mypage.profile.update') }}"
    method="POST"
    enctype="multipart/form-data"
>
    @csrf
    @method('PATCH')

    <div>
        @if ($user->profile && $user->profile->profile_image)
            <img
                src="{{ asset($user->profile->profile_image) }}"
                alt="プロフィール画像"
                width="100"
            >
        @endif

        <input
            type="file"
            name="profile_image"
            accept=".jpg,.jpeg,.png"
        >

        @error('profile_image')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label>ユーザー名</label>

        <input
            type="text"
            name="name"
            value="{{ old('name', $user->name) }}"
        >

        @error('name')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label>郵便番号</label>

        <input
            type="text"
            name="postal_code"
            value="{{ old('postal_code', $user->profile?->postal_code) }}"
        >

        @error('postal_code')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label>住所</label>

        <input
            type="text"
            name="address"
            value="{{ old('address', $user->profile?->address) }}"
        >

        @error('address')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label>建物名</label>

        <input
            type="text"
            name="building"
            value="{{ old('building', $user->profile?->building) }}"
        >

        @error('building')
            <p>{{ $message }}</p>
        @enderror
    </div>

    <button type="submit">
        更新する
    </button>
</form>