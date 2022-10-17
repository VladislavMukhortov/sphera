@extends('layouts.app')
@section('title', 'SpHERE - Изменить параметр')
@section('content')

    <x-breadcrumbs
        :title="\App\Models\Setting::TRANSLATIONS[$setting->parameter] ?? $setting->parameter"
        :parents="[
        [
            'name' => 'Настройки сервиса',
            'link' => route('settings.index')
        ]
    ]"
    ></x-breadcrumbs>

    <div class="box">
        <div class="box-title">Изменить параметр</div>
        <div class="form">
            <form id="updateSettingForm" action="{{route('settings.update', $setting)}}" method="post">
                <input type="hidden" class="pin-confirmation" name="pin">
                @csrf
                @method('patch')
                <div class="input-group">
                    <input type="text" name="parameter"
                           value="{{\App\Models\Setting::TRANSLATIONS[$setting->parameter] ?? $setting->parameter}}"
                           disabled>
                    <label for="parameter">Название</label>
                </div>
                <div class="input-group">
                    <input type="number" name="value" value="{{$setting->value}}" min="0" max="5" step="0.1">
                    <label>Значение</label>
                </div>
            </form>
            <div class="action-btns" style="margin-top: 20px">
                <button onclick="updateSetting()" class="btn" style="background-color: #f78ca0">Сохранить изменения</button>
                <a href="{{route('settings.index')}}" class="btn-small">Отменить</a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function updateSetting(){
                submitWithConfimation('updateSettingForm');
            }
        </script>
    @endpush

@endsection
