<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
          crossorigin="anonymous"
    />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/loading.css') }}" rel="stylesheet">

    <!-- Toastr css -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
</head>
<body>
    <div id="app"
         @guest()
            class="guest-background"
            style="background-image: url({{ asset('images/backgrounds/config-course-deliverable.gif') }})"
         @endguest>

        <header>
            @include('layouts.navbar')
        </header>

        <main class="py-4">
            @yield('content')
        </main>

        @guest()
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Информация</div>
                            <div class="card-body">
                                <p>
                                    Областта на онлайн обучението е сравнително млада, понеже хората отказваха да я приемат напълно
                                    и не виждаха предимствата от нея. Необходимостта от този тип технология се усети най-много
                                    по време на пандемията от <i>COVID-19</i>.
                                </p>
                                <p>
                                    За да не се прекъсне обучението на студенти и ученици
                                    всички бяха принудени да се обърнат към алтернативата, а именно отдалеченото обучение.
                                    Създаването на такъв тип софтуер изисква обширни проучвания, възползване от експертно мнение,
                                    добро познаване на структури от данни за ефективното им използване и прилагане,
                                    но може би най-важното, добро познаване на процесите в една образователна система.
                                </p>
                                <p>
                                    <i>Classroom helper</i> дава на платформата <i>Google Classroom</i> ценен инструмент,
                                    с който преподавател може да организира информация за всяка група студенти,
                                    в която преподава.
                                </p>
                                <p>
                                    <i>Classroom helper</i> не претендира да е завършен продукт, напротив,
                                    стремежът е да се подобрява и расте, като се увеличават и изискванията от сферата,
                                    която обслужва.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endguest()

    </div>

    <!-- Toastr js -->
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    @auth()
        @if(Auth::user()->is_admin)
            <script type="text/javascript" src="{{ asset('js/admin/adminEmails.js') }}" defer></script>
            <script type="text/javascript" src="{{ asset('js/admin/adminTeachers.js') }}" defer></script>
            <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/exacti/floating-labels@latest/floating-labels.min.css" media="screen">
        @endif
        @if(\Session::get('connected_with_classroom'))
            <script type="text/javascript" src="{{ asset('js/user.js') }}" defer></script>
        @endif
    @endauth

    {!! Toastr::message() !!}
</body>
</html>
