@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    {{ __('You are logged in!') }}
                </div>
                @auth
                    <div>
                        <a href="{{ route('login.google.classroom') }}" class="btn btn-success">
                            Connect the Google Classroom
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
