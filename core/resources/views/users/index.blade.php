@extends('layouts.app')
@section('title', 'SpHERE - список пользователей')
@section('content')

    <x-breadcrumbs
        title="Пользователи"
    ></x-breadcrumbs>

    <div class="box">
        <div class="box-title">Пользователи</div>
        <livewire:users.index-table>
    </div>
    <livewire:scripts>
@endsection
