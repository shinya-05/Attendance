@extends('layouts.app')

@section('main')
<div class="main-container">
  <h1 class="main-ttl">会員登録</h1>
  <form action="/register" method="post">
    @csrf
    <div class="input-container">
      <label class="input-container__label" for="name">ユーザー名</label>
      <input class="input-container__input" type="text" name="name" value="{{ old('name') }}">
      @error('name')
      <p>{{ $message }}</p>
      @enderror
    </div>
    <div class="input-container">
      <label class="input-container__label" for="email">メールアドレス</label>
      <input class="input-container__input" type="email" name="email" value="{{ old('email') }}">
      @error('email')
      <p>{{ $message }}</p>
      @enderror
    </div>
    <div class="input-container">
      <label class="input-container__label" for="password">パスワード</label>
      <input class="input-container__input" type="password" name="password">
      @error('password')
      <p>{{ $message }}</p>
      @enderror
    </div>
    <div class="input-container">
      <label class="input-container__label" for="password">確認用パスワード</label>
      <input class="input-container__input" type="password" name="password_confirmation">
      @error('password_confirmation')
      <p>{{ $message }}</p>
      @enderror
    </div>
    <div class="btn-container">
      <input type="submit" value="登録する">
    </div>
  </form>
  <a class="transition-link" href="/login">ログインはこちら</a>
</div>
@endsection