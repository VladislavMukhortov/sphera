@extends('layouts.app')
@section('title', 'SpHERE - список администраторов')
@section('content')

    <x-breadcrumbs
        title="Администраторы"
    ></x-breadcrumbs>


    <div class="box">
        <div class="box-title">Список администраторов</div>
        <div class="flex-space mb-40">
            <a class="btn" href="{{route('staff.create')}}">Создать администратора</a>
        </div>
        <livewire:staff.index-table>
    </div>
    <livewire:scripts>
@endsection
