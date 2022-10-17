<div>
    <div class="table col4">
        <div class="table-head">
            <div class="table-row">
                <div><button class="sort-button" wire:click="sort('ip')">ip</button></div>
                <div><button class="sort-button" wire:click="sort('browser_ver')">Версия браузера</button></div>
                <div><button class="sort-button" wire:click="sort('os_ver')">Операционная система</button></div>
                <div><button class="sort-button" wire:click="sort('created_at')">Дата входа</button></div>
            </div>
        </div>
        <div class="table-body">
            @foreach($logs as $log)
                <div class="table-row">
                    <div>{{$log->ip}}</div>
                    <div>{{$log->browser}} {{$log->browser_ver}}</div>
                    <div>{{$log->os}} {{$log->os_ver}}</div>
                    <div>{{$log->created_at->format('d.m.Y H:i:s')}}</div>
                </div>
            @endforeach
        </div>
    </div>
    {{$logs->links()}}
</div>
