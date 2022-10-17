<div>
    <div class="user-panel">
        <div class="user-panel-logo">
            <a href="{{route('home')}}"><img src="{{asset('img/logo.svg')}}" alt="SpHERE"></a>
        </div>
{{--        <div class="user-panel-info">--}}
{{--            <a href="{{route('settings.index')}}">{{ Auth::user()->name }}</a>--}}
{{--        </div>--}}
        <div class="user-panel-button">
            <a href="" class="logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();"></a>
            <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        </div>
    </div>
    <div class="navigation">
        <div class="navigation-title">Меню</div>
        <div class="navigation-menu">
            <nav>
                @forelse($menu as $m)
                    <a href="{{$m['href']}}" class="navigation-menu-item {{$m['active']}}"><span class="mdi {{$m['icon']}}"></span>{{$m['text']}}</a>
                @empty
                @endforelse
            </nav>
        </div>
    </div>

</div>
