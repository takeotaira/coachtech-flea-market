<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メール認証</title>
</head>
<body>

    <p>
        登録していただいたメールアドレスに認証メールを送付しました。
        メール認証を完了してください。
    </p>

    <a href="http://localhost:8025">
        認証はこちらから
    </a>

    <form action="{{ route('verification.send') }}" method="POST">
        @csrf

        <button type="submit">
            認証メールを再送する
        </button>
    </form>

    @if (session('status') === 'verification-link-sent')
        <p>認証メールを再送しました。</p>
    @endif

</body>
</html>