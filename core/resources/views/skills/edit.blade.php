@extends('layouts.app')
@section('title', 'SpHERE - Обновить область знаний')
@section('content')

    <x-breadcrumbs
        :title="'Обновить область знаний ' . $skill->title"
        :parents="[
        [
            'name' => 'Области знаний',
            'link' => route('skills.index')
        ]
    ]"
    ></x-breadcrumbs>

    <div class="box">
        <div class="box-title">Обновить область знаний</div>
        <div class="form">
            <form action="{{route('skills.update', $skill)}}" method="post">
                @csrf
                @method('patch')
                <div class="input-group">
                    <input type="text" name="title_ru" value="{{$skill->locale->title}}">
                    <label for="title_ru">Название области (RU)</label>
                </div>
                <div class="input-group">
                    <input type="text" name="title_en" value="{{$skill->locales()->firstWhere('lang', 'en')->title}}">
                    <label for="title_en">Название области (EN)</label>
                </div>
                <div class="input-group">
                    <input type="text" name="title_cn" value="{{$skill->locales()->firstWhere('lang', 'cn')->title}}">
                    <label for="title_cn">Название области (Chinese)</label>
                </div>
                <div class="input-group">
                    <select name="is_allowed" id="allow" class="nice-select">
                        <option value="1" @if($skill->is_allowed) selected @endif>Да</option>
                        <option value="0" @if(!$skill->is_allowed) selected @endif>Нет</option>
                    </select>
                    <label>Разрешен</label>
                </div>
                <div class="input-group">
                    <select name="parent_id" id="select" class="nice-select">
                        <option value="">Отсутствует</option>
                        @foreach ($parents as $parent_id => $parent_title)
                            <option value="{{$parent_id}}"
                                    @if($skill->parent_id == $parent_id) selected @endif>{{$parent_title}}</option>
                        @endforeach
                    </select>
                    <label>Родительская область</label>
                </div>
                <div class="form-button">
                    <input type="submit" value="Обновить" class="btn">
                    <a href="{{route('skills.index')}}" class="btn-small">Отменить</a>
                </div>
            </form>
            <div class="action-btns" style="margin-top: 20px">
                <button onclick="deleteSkill()" class="btn" style="background-color: #f78ca0">Удалить</button>
            </div>
        </div>
    </div>

    <form id="deleteskill" action="{{route('skills.destroy', $skill)}}" method="POST" style="display: none;">
        <input type="hidden" class="pin-confirmation" name="pin">
        @method('delete')
        @csrf
    </form>

    @push('scripts')
        <script>
            function deleteSkill(){
                submitWithConfimation('deleteskill');
            }
        </script>
    @endpush

@endsection
