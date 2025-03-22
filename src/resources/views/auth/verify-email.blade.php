@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">

@section('main')
    <div class="header__wrap">
        <div class="header__text">
            {{ __('登録していただいたメールアドレスに認証メールを送信しました。') }}
        </br>{{ __('メール認証を完了してください。') }}
        </div>
    </div>
    <div class="body__wrap">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="form__input">
                {{ __('確認メールを再送信する') }}
            </button>
        </form>

    </div>
@endsection