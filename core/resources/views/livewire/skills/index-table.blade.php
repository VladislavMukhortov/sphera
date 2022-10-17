<div>
    <div class="filters mb-20">
        <div class="filters-item input-date-range">
            <label for="">Название</label>
            <input id="name" type="text" wire:model="search"/>
        </div>
    </div>
    <div class="table col4">
        <div class="table-head">
            <div class="table-row">
                <div><button class="sort-button" wire:click="sort('id')">Идентификатор</button></div>
                <div><button class="sort-button" wire:click="sort('title')">Название</button></div>
                <div><button class="sort-button" wire:click="sort('parent_id')">Родительская область</button></div>
                <div><button class="sort-button" wire:click="sort('is_allowed')">Разрешен</button></div>
            </div>
        </div>
        <div class="table-body">
            @forelse($skill as $ability)
                <div class="table-row">
                    <div>{{$ability->id}}</div>
                    <div><a href="{{route('skills.edit', $ability)}}" class="inline-link">{{$ability->locale->title ?? 'Нет данных'}}</a></div>
                    <div><a href="{{route('skills.edit', $ability)}}" class="inline-link">{{$ability->parent->locale->title ?? 'Нет данных'}}</a></div>
                    <div><a href="{{route('skills.edit', $ability)}}" class="inline-link">{{$ability->is_allowed ? 'Да' : 'Нет'}}</a></div>
                </div>
            @empty
                <div class="empty">Нет данных</div>
            @endforelse
        </div>
    </div>
    {{$skill->links()}}
</div>
