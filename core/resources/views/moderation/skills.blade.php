@extends('layouts.app')
@section('title', 'SpHERE - модерация')
@section('content')

    <x-breadcrumbs
        :title="'Модерация областей знаний'"
        :parents="[
        [
            'name' => 'Модерация',
            'link' => route('moderation.index')
        ],
    ]"
    ></x-breadcrumbs>

    <div class="box">
        <div class="box-title">Модерация областей знаний</div>
        <livewire:moderation.skills-table>
    </div>
    <livewire:scripts>
@endsection
