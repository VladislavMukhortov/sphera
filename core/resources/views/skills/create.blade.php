@extends('layouts.app')
@section('title', 'SpHERE - Создать область знаний')
@section('content')
    <x-breadcrumbs
        title="Создать область знаний"
        :parents="[
        [
            'name' => 'Области знаний',
            'link' => route('skills.index')
        ]
    ]"
    ></x-breadcrumbs>

    <div class="box">
        <div class="box-title">Создать область знаний</div>
        <div class="form">
            <form action="{{route('skills.store')}}" method="post">
                @csrf
                <div class="input-group">
                    <input type="text" name="title_ru" value="">
                    <label for="title_ru">Название области (RU)</label>
                </div>
                <div class="input-group">
                    <input type="text" name="title_en" value="">
                    <label for="title_en">Название области (EN)</label>
                </div>
                <div class="input-group">
                    <input type="text" name="title_cn" value="">
                    <label for="title_cn">Название области (Chinese)</label>
                </div>
                <div class="input-group">
                    <select name="parent_id" id="select" class="nice-select">
                        <option value="">Отсутствует</option>
                        @foreach ($parents as $parent_id => $parent_title)
                            <option value="{{$parent_id}}">{{$parent_title}}</option>
                        @endforeach
                    </select>
                    <label>Родительская область</label>
                </div>
                <div class="form-button">
                    <input type="submit" value="Создать" class="btn">
                    <a href="{{route('skills.index')}}" class="btn-small">Отменить</a>
                </div>
            </form>
        </div>
    </div>

@endsection
