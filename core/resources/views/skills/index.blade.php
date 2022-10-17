@extends('layouts.app')
@section('title', 'SpHERE - список областей знаний')
@section('content')

    <x-breadcrumbs
        title="Области знаний"
    ></x-breadcrumbs>

    <div class="box">
        <div class="box-title">Области знаний</div>
        <div class="flex-space mb-40">
            <a class="btn" href="{{route('skills.create')}}">Создать область</a>
        </div>
        <livewire:skills.index-table>
    </div>
    <livewire:scripts>
@endsection
