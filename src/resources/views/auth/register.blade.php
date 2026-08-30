<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>会員登録</title>
</head>
<body>

    <h1>会員登録</h1>

    <form action="/register" method="POST">
        @csrf

        <div>
            <label for="name">ユーザー名</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
            >
        </div>

        <div>
            <label for="email">メールアドレス</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
            >
        </div>

        <div>
            <label for="password">パスワード</label>
            <input
                type="password"
                id="password"
                name="password"
            >
        </div>

        <div>
            <label for="password_confirmation">確認用パスワード</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
            >
        </div>

        <button type="submit">登録する</button>
    </form>

</body>
</html>