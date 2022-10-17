@extends('layouts.app')
@section('title', 'SpHERE - модерация')
@section('content')

    <x-breadcrumbs
        title="Модерация"
    ></x-breadcrumbs>

    <div class="box">
        <div class="box-title">Пользовательский контент</div>
        <div class="action-btns">
            <a class="btn" href="{{route('moderation.skills')}}">Области знаний ({{$skills_count}})</a>
        </div>
    </div>
@endsection
