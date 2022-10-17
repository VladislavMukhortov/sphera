@extends('layouts.app')
@section('title', 'SpHERE - Создать администратора')
@section('content')
    <x-breadcrumbs
        title="Создать администратора"
        :parents="[
        [
            'name' => 'Администраторы',
            'link' => route('staff.index')
        ]
    ]"
    ></x-breadcrumbs>

    <div class="box">
        <div class="box-title">Создать администратора</div>
        <div class="form">
            <form action="{{route('staff.store')}}" method="post">
                @csrf
                <div class="input-group">
                    <input type="text" name="name">
                    <label for="name">Имя</label>
                </div>
                <div class="input-group">
                    <input type="email"name="email">
                    <label for="email">E-mail</label>
                </div>
                <div class="input-group">
                    <input id="password" type="tel" name="password" value="{{\Str::random(8)}}">
                    <button class="mini-btn" id="generate" type="button" onclick="__generate()">Сгенерировать</button>
                    <label for="phone">Пароль</label>
                </div>
                <div class="input-group">
                    <select name="access_level" id="select"  required class="nice-select">
                        @forelse (\App\Models\Staff::ROLES as $roleValue => $roleName)
                            <option value="{{$roleValue}}">{{$roleName}}</option>
                            @endforeach
                    </select>
                    <label>Уровень доступа</label>
                </div>
                <div class="form-button">
                    <input type="submit" value="Создать" class="btn">
                    <a href="{{route('staff.index')}}" class="btn-small">Отменить</a>
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
