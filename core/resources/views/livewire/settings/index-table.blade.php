<div>
{{--    <div class="filters mb-20">--}}
{{--        <div class="filters-item input-date-range">--}}
{{--            <label for="">Название</label>--}}
{{--            <input id="name" type="text" wire:model="search"/>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="table col3">
        <div class="table-head">
            <div class="table-row">
                <div><button class="sort-button" wire:click="sort('id')">Идентификатор</button></div>
                <div><button class="sort-button" wire:click="sort('parameter')">Параметр</button></div>
                <div><button class="sort-button" wire:click="sort('value')">Значение</button></div>
            </div>
        </div>
        <div class="table-body">
            @forelse($settings as $setting)
                <div class="table-row">
                    <div>{{$setting->id}}</div>
                    <div><a href="{{route('settings.edit', $setting)}}"
                            class="inline-link">{{\App\Models\Setting::TRANSLATIONS[$setting->parameter] ?? $setting->parameter}}</a>
                    </div>
                    <div>
                        <a href="{{route('settings.edit', $setting)}}"
                           class="inline-link">{{$setting->value ?? 'Нет данных'}}</a>
                    </div>
                </div>
            @empty
                <div class="empty">Нет данных</div>
            @endforelse
        </div>
    </div>
    {{$settings->links()}}
</div>
