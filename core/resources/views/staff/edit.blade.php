@extends('layouts.app')
@section('title', 'SpHERE - Обновить администратора')
@section('content')

    <x-breadcrumbs
        :title="'Обновить администратора ' . $staff->name"
        :parents="[
        [
            'name' => 'Администраторы',
            'link' => route('staff.index')
        ],
        [
            'name' => $staff->name,
            'link' => route('staff.show', $staff)
        ]
    ]"
    ></x-breadcrumbs>


    <div class="box">
        <div class="box-title">Обновить администратора</div>
        <div class="form">
            <form action="{{route('staff.update', $staff)}}" method="post">
                @csrf
                @method('patch')
                <div class="input-group">
                    <input type="text" name="name" value="{{$staff->name}}">
                    <label for="name">Имя</label>
                </div>
                <div class="input-group">
                    <input type="email"name="email" value="{{$staff->email}}">
                    <label for="email">E-mail</label>
                </div>
                <div class="input-group">
                    <input id="password" type="tel" name="password" value="">
                    <button class="mini-btn" id="generate" type="button" onclick="__generate()">Сгенерировать</button>
                    <label for="phone">Пароль</label>
                </div>
                <div class="input-group">
                    <select name="access_level" id="select"  required class="nice-select">
                        @forelse (\App\Models\Staff::ROLES as $roleValue => $roleName)
                            <option value="{{$roleValue}}" @if($staff->access_level == $roleValue) selected @endif>{{$roleName}}</option>
                            @endforeach
                    </select>
                    <label>Уровень доступа</label>
                </div>
                <div class="form-button">
                    <input type="submit" value="Обновить" class="btn">
                    <a href="{{route('staff.show', $staff)}}" class="btn-small">Отменить</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')

        <script>

            function __generate()
            {
                document.getElementById('password').value = Math.random().toString(36).slice(-8) + ('!@#$%^&*()').charAt(Math.floor(Math.random() * ('!@#$%^&*()').length));
            }

        </script>
    @endpush

@endsection
