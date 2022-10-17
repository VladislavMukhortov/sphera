@extends('layouts.app')
@section('title', 'SpHERE - Информация о администраторе '.$staff->name)
@section('content')

    <x-breadcrumbs
        :title="'Информация о ' . $staff->name"
        :parents="[
        [
            'name' => 'Администраторы',
            'link' => route('staff.index')
        ]
    ]"
    ></x-breadcrumbs>

    <div class="box">
        <div class="grid grid-2">
            <div>
                <div class="more-info">
                    <h3 class="more-info-title"><span>Информация об админе</span></h3>
                    <div class="table col2 lines mb-40">
                        <div class="table-row">
                            <div>Статус</div>
                            <div><span class="label {{$staff->activity_class}}">{{$staff->activity}}</span></div>
                        </div>
                        <div class="table-row">
                            <div>Имя</div>
                            <div>{{$staff->name ?? 'Нет данных'}}</div>
                        </div>
                        <div class="table-row">
                            <div>E-mail</div>
                            <div>{{$staff->email ?? 'Нет данных'}}</div>
                        </div>
                        <div class="table-row">
                            <div>Уровень доступа</div>
                            <div>{{$staff->role}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="more-info">
                    <h3 class="more-info-title"><span>Последние действия</span></h3>
                    <div class="table col4">
                        <div class="table-head">
                            <div class="table-row">
                                <div>Цель</div>
                                <div>Поле</div>
                                <div>Действие</div>
                                <div>Дата</div>
                            </div>
                        </div>
                        <div class="table-body">
                            @forelse($lastActions as $action)
                                <div class="table-row">
                                    <div>{{$action->targeter}} {{$action->targetable->name }}</div>
                                    <div>{{$action->field}}</div>
                                    <div>{{$action->oldv }} 🠒 {{$action->newv}}</div>
                                    <div>{{$action->created_at->format('d.m.Y H:i:s')}}</div>
                                </div>
                            @empty
                                <div class="empty">Нет данных</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="more-info-btns mb-40">
            <a href="{{route('staff.signins', $staff)}}">История входов</a>
        </div>
        @can('update', $staff)
            <div class="action-btns">
                <a class="btn" href="{{route('staff.edit',$staff)}}">Редактировать</a>
                @if($staff->is_enable)
                    <button onclick="blockAdmin()" class="btn-small">Заблокировать</button>
                @else
                    <button onclick="unblockAdmin()" class="btn-small">Разблокировать</button>
                @endif
            </div>
        @endcan
    </div>

    <form id="blockadmin" action="{{route('staff.block', $staff)}}" method="POST" style="display: none;">
        <input type="hidden" class="pin-confirmation" name="pin">
        @method('patch')
        @csrf
    </form>

    <form id="unblockadmin" action="{{route('staff.unblock', $staff)}}" method="POST" style="display: none;">
        <input type="hidden" class="pin-confirmation" name="pin">
        @method('patch')
        @csrf
    </form>

    @push('scripts')
        <script>
            function blockAdmin(){
                submitWithConfimation('blockadmin');
            }
            function unblockAdmin(){
                submitWithConfimation('unblockadmin');
            }
        </script>
    @endpush

@endsection
