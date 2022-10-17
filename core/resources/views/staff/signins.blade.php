@extends('layouts.app')
@section('title', 'SpHERE - Логины администратора')
@section('content')
    <x-breadcrumbs
        :title="'Логин ' . $staff->name"
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
        <div class="box-title">Логины администратора</div>
        <div class="more-info-btns mb-20">
            <a href="{{route('staff.show', $staff)}}">Вернуться к {{$staff->name}}</a>
        </div>
        <livewire:common.sign-in-table :whoId="$staff->id" whoType="staff">
    </div>
    <livewire:scripts>
@endsection
