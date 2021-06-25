@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div id="card-header-students" class="card-header">
                        <div class="align-self-center">
                            Студенти
                        </div>
                    </div>
                <div class="card-body">
                    <div class="col text-center">
                        <a href="{{ route('classroom.students.export', $courseId) }}" id="export-excel" class="btn btn-outline-primary">Експорт в <i>Excel</i></a>
                    </div>
                    <div class="table-responsive">
                        @include('classroom.tables.students')
                        @include('classroom.loading')
                    </div>
                </div>
            </div>
    </div>
@endsection
