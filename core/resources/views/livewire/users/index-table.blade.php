<div>
    <div class="filters mb-20">
        <div class="filters-item input-date-range">
            <label for="">Имя или E-mail</label>
            <input id="name" type="text" wire:model="search"/>
        </div>
    </div>
    <div class="table col6">
        <div class="table-head">
            <div class="table-row">
                <div><button class="sort-button" wire:click="sort('first_name')">Имя</button></div>
                <div><button class="sort-button" wire:click="sort('last_name')">Фамилия</button></div>
                <div><button class="sort-button" wire:click="sort('email')">E-mail</button></div>
                <div><button class="sort-button" wire:click="sort('phone')">Телефон</button></div>
                <div><button class="sort-button" wire:click="sort('gender')">Пол</button></div>
                <div><button class="sort-button" wire:click="sort('created_at')">Дата регистрации</button></div>
            </div>
        </div>
        <div class="table-body">
            @forelse($users as $user)
                <div class="table-row">
                    <div><a href="{{route('users.show', $user)}}" class="inline-link">{{$user->first_name ?? 'Нет данных'}}<span class="tagid">#{{$user->id}}</span></a></div>
                    <div><a href="{{route('users.show', $user)}}" class="inline-link">{{$user->last_name ?? 'Нет данных'}}</a></div>
                    <div><a href="{{route('users.show', $user)}}" class="inline-link">{{$user->email ?? 'Нет данных'}}</a></div>
                    <div><a href="{{route('users.show', $user)}}" class="inline-link">{{$user->phone ?? 'Нет данных'}}</a></div>
                    <div>{{$user->gender}}</div>
                    <div>{{$user->created_at->format('d.m.Y H:i')}}</div>
                </div>
            @empty
                <div class="empty">Нет данных</div>
            @endforelse
        </div>
    </div>
    {{$users->links()}}
</div>
