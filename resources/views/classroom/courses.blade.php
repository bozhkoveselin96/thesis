@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div id="card-header-courses" class="card-header">
                        <div class="align-self-center">
                            Курсове
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @include('classroom.tables.courses')
                            @include('classroom.loading')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
