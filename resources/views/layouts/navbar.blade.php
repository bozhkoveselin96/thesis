<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-graduation-cap"></i>
            {{ config('app.name', 'Classroom helper') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    @if(Route::has('login.google'))
                        <li class="nav-item">
                            <a class="btn btn-danger" href="{{ route('login.google') }}">Вход с Google</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <img class="avatar" src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">

                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right-custom" aria-labelledby="navbarDropdown">
                            @if(Auth::user()->is_admin)
                                <a class="navbar-btn-size btn btn-outline-primary" href="{{ route('email.index') }}">
                                    <i class="fal fa-envelope"></i>&nbsp;Имейли
                                </a>
                                <a class="navbar-btn-size btn btn-outline-dark" href="{{ route('admin.teacher.index') }}">
                                    <i class="fal fa-user-chart"></i>&nbsp;Преподаватели
                                </a>
                            @endif

                            <a class="navbar-btn-size btn btn-outline-success" href="{{ route('login.google.classroom') }}">
                                <i class="fas fa-users"></i>&nbsp;Google класна стая
                            </a>

                            <a class="navbar-btn-size btn btn-outline-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); $('#logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>&nbsp;Изход
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
