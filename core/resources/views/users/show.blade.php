@extends('layouts.app')
@section('title', 'SpHERE - Информация о пользователе '.$user->first_name)
@section('content')

<x-breadcrumbs
    :title="'Информация о ' . $user->first_name"
    :parents="[
        [
            'name' => 'Пользователи',
            'link' => route('users.index')
        ]
    ]"
></x-breadcrumbs>

<div class="box">
    <div class="grid grid-1">
        <div>
            <div class="more-info">
                <h3 class="more-info-title">
                    <span>Информация о {{$user->first_name ?? 'Нет данных'}}</span>
                </h3>
                @isset($user->photo)
                <div class="avatar">
                    <img src="{{$user->photo}}" alt="">
                </div>
                @endisset
                <div class="table col2 lines mb-40">
                    <div class="table-row">
                        <div>ID</div>
                        <div>{{$user->id}}</div>
                    </div>
                    <div class="table-row">
                        <div>UUID</div>
                        <div>{{$user->uuid}}</div>
                    </div>
                    <div class="table-row">
                        <div>Имя</div>
                        <div>{{$user->first_name ?? 'Нет данных'}}</div>
                    </div>
                    <div class="table-row">
                        <div>E-mail</div>
                        <div>{{$user->email ?? 'Нет данных'}}</div>
                    </div>
                    <div class="table-row">
                        <div>Телефон</div>
                        <div>{{$user->phone ?? 'Нет данных'}}</div>
                    </div>
                    <div class="table-row">
                        <div>Страна</div>
                        <div>{{$user->country->locale->name ?? "-"}}</div>
                    </div>
                    <div class="table-row">
                        <div>Пол</div>
                        <div>{{$user->gender}}</div>
                    </div>
                    <div class="table-row">
                        <div>Язык интерфейса</div>
                        <div>{{$user->lang}}</div>
                    </div>
                    <div class="table-row">
                        <div>Дата регистрация</div>
                        <div>{{$user->created_at->format('d.m.Y H:i:s')}}</div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="more-info">
                <h3 class="more-info-title">
                    <span>Firebase</span>
                </h3>
                <div class="table col2 lines mb-40">
                    <div class="table-row">
                        <div>Токен</div>
                        <div>{{ $user->firebaseToken->token ?? 'Нет данных' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('update', $user)
        <div class="action-btns">
{{--            <a class="btn" href="{{route('users.edit',$user)}}">Редактировать</a>--}}
            @if($user->is_banned)
                <button onclick="unblockUser()" class="btn-small">Разблокировать</button>
            @else
                <button onclick="blockUser()" class="btn-small">Заблокировать</button>
            @endif
        </div>
    @endcan
</div>

<form id="blockuser" action="{{route('users.block', $user)}}" method="POST" style="display: none;">
    <input type="hidden" class="pin-confirmation" name="pin">
    @method('patch')
    @csrf
</form>

<form id="unblockuser" action="{{route('users.unblock', $user)}}" method="POST" style="display: none;">
    <input type="hidden" class="pin-confirmation" name="pin">
    @method('patch')
    @csrf
</form>

@push('scripts')
<script>
    function blockUser(){
        submitWithConfimation('blockuser');
    }
    function unblockUser(){
        submitWithConfimation('unblockuser');
    }
</script>
@endpush

@endsection
