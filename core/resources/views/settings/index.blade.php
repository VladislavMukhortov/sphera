@extends('layouts.app')
@section('title', 'SpHERE - Настройки сервиса')
@section('content')

    <x-breadcrumbs
        title="Базовые настройки сервиса"
    ></x-breadcrumbs>

    <div class="box">
        <div class="box-title">Настройки</div>
        <livewire:settings.index-table>
    </div>
    <livewire:scripts>
@endsection
