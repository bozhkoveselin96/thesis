<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
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
                            <a class="btn btn-danger" href="{{ route('login.google') }}">Login with Google</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <img class="avatar" src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">

                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            @if(Auth::user()->is_admin)
                                <a class="navbar-btn-size btn btn-outline-primary" href="{{ route('email.index') }}">
                                    <i class="fal fa-envelope"></i>&nbsp;{{ __('Emails') }}
                                </a>
                                <a class="navbar-btn-size btn btn-outline-dark" href="{{ route('admin.teacher.index') }}">
                                    &nbsp;&nbsp;&nbsp;<i class="fal fa-user-chart"></i>&nbsp;{{ __('Teachers') }}
                                </a>
                            @endif

                            @if(\Session::get('connected_with_classroom'))
                                <a class="navbar-btn-size btn btn-outline-secondary" href="{{ route('classroom.courses') }}">
                                    &nbsp;&nbsp;<i class="fal fa-graduation-cap"></i>&nbsp;{{ __('Courses') }}
                                </a>
                            @else
                                <a class="navbar-btn-size btn btn-outline-success" href="{{ route('login.google.classroom') }}">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-users"></i>&nbsp;{{ __('Classroom') }}
                                </a>
                            @endif

                            <a class="navbar-btn-size btn btn-outline-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); $('#logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>&nbsp;{{ __('Logout') }}
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
