@extends('layouts.app')
@section('title', 'SpHERE - Настройки')
@section('content')

    <x-breadcrumbs
        title="Рабочий стол"
    ></x-breadcrumbs>

    <div class="box">
        <div class="grid grid-2">
            <div>
                <div class="more-info">
                    <h3 class="more-info-title"><span>Персональные данные</span></h3>
                    <div class="table col2 lines mb-40">
                        <div class="table-row">
                            <div>Статус</div>
                            <div>{{Auth::user()->activity}}</div>
                        </div>
                        <div class="table-row">
                            <div>Имя</div>
                            <div>{{Auth::user()->name ?? 'Нет данных'}}</div>
                        </div>
                        <div class="table-row">
                            <div>E-mail</div>
                            <div>{{Auth::user()->email ?? 'Нет данных'}}</div>
                        </div>
                        <div class="table-row">
                            <div>Уровень доступа</div>
                            <div>{{Auth::user()->role}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="more-info">
                    <h3 class="more-info-title"><span>Выполнено работ</span></h3>
                </div>
            </div>
        </div>
        <div class="more-info-btns mb-40">
            <a href="{{route('settings.signins')}}">История входов</a>
        </div>
        <div class="action-btns">
            <a class="btn" href="{{route('settings.password')}}">Сменить пароль</a>
            <a class="btn" href="{{route('settings.pin')}}">Установить пин</a>
            <a class="btn" href="{{route('settings.pd')}}">Персональные данные</a>
        </div>
    </div>


@endsection
