<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SpHERE - Кабинет администратора')</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicons/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('favicons/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('favicons/safari-pinned-tab.svg')}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link href="{{ asset('css/materialdesignicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
<div class="main">
    <div class="navigation-wrap">
        <x-menu />
        <button class="mobile close" id="close-menu"></button>
    </div>
    <div class="content-wrap">
        <div class="mobile">
            <button id="menu" class="menu">Меню</button>
        </div>
        @yield('content')
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/staff.js') }}"></script>
@stack('scripts')
<script type="text/javascript">
    @if($errors->any())
    @foreach ($errors->all() as $error)
    show_message('error',"Ошибка",'{{ $error }}');
    @break
    @endforeach
    @endif
    @if (\Session::has('success'))
    show_message("success", "Успех!","{{ \Session::get('success') }}");
    @endif
    @if (\Session::has('error'))
    show_message("error", "Ошибка!","{{ \Session::get('error') }}");
    @endif
</script>
</body>
</html>
