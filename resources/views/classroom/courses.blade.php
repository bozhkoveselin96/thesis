@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('classroom.tables.courses')

                @include('classroom.loading')
            </div>
        </div>
    </div>
@endsection
