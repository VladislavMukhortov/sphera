<div class="breadcrumbs">
    <div class="breadcrumbs-title">
        {{$title}}
    </div>
    <div class="breadcrumbs-list">
        <a href="{{route('home')}}">Главная</a>
        @forelse($parents as $pr)
            <a href="{{$pr['link']}}">{{$pr['name']}}</a>
        @empty
        @endforelse
    </div>
</div>
