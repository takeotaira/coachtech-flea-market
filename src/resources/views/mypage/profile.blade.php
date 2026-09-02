<h1>プロフィール設定</h1>

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">ログアウト</button>
</form>