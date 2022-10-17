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
                <div>Решение:</div>
            </div>
        </div>
        <div class="table-body">
            @forelse($skill as $ability)
                <div class="table-row">
                    <div>{{$ability->id}}</div>
                    <div><a href="{{route('skills.edit', $ability)}}" class="inline-link">{{$ability->title ?? 'Нет данных'}}</a></div>
                    <div><a href="{{route('skills.edit', $ability)}}" class="inline-link">{{$ability->parent->title ?? 'Нет данных'}}</a></div>
                    <div class="action-btns">
                        <input type="submit" form="acceptskill{{$ability->id}}" value="Принять" class="btn" style="font-size: 13px">
                        <input type="submit" form="declineskill{{$ability->id}}" value="Отклонить" class="btn" style="font-size: 13px">
                    </div>
                </div>
                <form id="acceptskill{{$ability->id}}" hidden action="{{route('moderation.skill.accept', $ability)}}"
                      method="POST" style="display: none;">
                    @csrf
                </form>
                <form id="declineskill{{$ability->id}}" hidden action="{{route('moderation.skill.decline', $ability)}}"
                      method="POST" style="display: none;">
                    @csrf
                </form>
            @empty
                <div class="empty">Нет данных</div>
            @endforelse
        </div>
    </div>
    {{$skill->links()}}
</div>
