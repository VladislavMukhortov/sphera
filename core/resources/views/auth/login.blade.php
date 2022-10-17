<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SpHERE - Вход в кабинет администратора</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicons/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('favicons/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('favicons/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
<div class="auth">
    <div class="auth-wrap">
        <div class="auth-logo">
            <a href="{{route('home')}}"><img src="{{asset('img/logo.svg')}}" alt="SpHERE"></a>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            @error('email')
            <div class="auth-invalid" role="alert">Неверно введён e-mail или пароль</div>
            @enderror
            @error('password')
            <div class="auth-invalid" role="alert">Неверно введён e-mail или пароль</div>
            @enderror
            <div class="mb-40">
                <div class="auth-input">
                    <label for="email"><span class="mdi mdi-email-check"></span></label>
                    <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E-mail">
                </div>
                <div class="auth-input">
                    <label for="password"><span class="mdi mdi-key-variant"></span></label>
                    <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Пароль">
                </div>
            </div>
            <div style="margin-left: 20px">Test-admin: example@domain.com / 11111111 (pin 123456)</div>
            <div class="auth-bts">
                <button type="submit" class="auth-btn">Войти</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
