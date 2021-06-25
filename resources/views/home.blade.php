@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div id="welcome" class="card-header">
                    <h3>{{ __('Добре дошли в Classroom helper!') }}</h3>
                </div>

                <div class="card-body" style="background-image: url({{ asset('images/backgrounds/school-theme.jpg') }})">
                    <div class="bottom-space">
                        <img id="education-gif-without-border" src="{{ asset('images/gifs/learning.gif') }}" class="center-gif education">
                    </div>

                    <div class="bottom-space">
                        <img src="{{ asset('images/gifs/arrow.gif') }}" class="center-gif" id="arrow-gif">
                    </div>

                    <div>
                        <img src="{{ asset('images/gifs/e-learning.gif') }}" class="center-gif education">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
