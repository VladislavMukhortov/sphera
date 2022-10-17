<div>
    <div class="filters mb-20">
        <div class="filters-item input-date-range">
            <label for="">Имя или E-mail</label>
            <input id="name" type="text" wire:model="search"/>
        </div>
    </div>
    <div class="table col4">
        <div class="table-head">
            <div class="table-row">
                <div><button class="sort-button" wire:click="sort('name')">Имя</button></div>
                <div><button class="sort-button" wire:click="sort('email')">E-mail</button></div>
                <div><button class="sort-button" wire:click="sort('access_level')">Уровень доступа</button></div>
                <div><button class="sort-button" wire:click="sort('is_enable')">Активность</button></div>
            </div>
        </div>
        <div class="table-body">
            @forelse($staff as $user)
                <div class="table-row">
                    <div><a href="{{route('staff.show', $user)}}" class="inline-link">{{$user->name ?? 'Нет данных'}}<span class="tagid">#{{$user->id}}</span></a></div>
                    <div><a href="{{route('staff.show', $user)}}" class="inline-link">{{$user->email ?? 'Нет данных'}}</a></div>
                    <div>{{$user->role}}</div>
                    <div>{{$user->activity}}</div>
                </div>
            @empty
                <div class="empty">Нет данных</div>
            @endforelse
        </div>
    </div>
    {{$staff->links()}}
</div>
