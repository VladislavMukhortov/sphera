@extends('layouts.app')
@section('content')
    <x-breadcrumbs
        title="Рабочий стол"
    ></x-breadcrumbs>

    <div class="box">
        <div class="box-title">Поиск</div>
        <div class="more-info">
            <h3 class="more-info-title"><span>Быстрый поиск по сервису</span></h3>
            <form action="#" method="get">
                @csrf
                <input type="text" name="search" class="popup__search-input" placeholder="Поиск ...">
                <div>
                    <div class="search-radio-group">
                        <input type="radio" name="_type" value="clients" id="s-clients" checked>
                        <label for="s-clients">Пользователи</label>
                    </div>
                </div>
                <input type="submit" value="Найти" class="btn">
            </form>
        </div>
    </div>

@endsection
