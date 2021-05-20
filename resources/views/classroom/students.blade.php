@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="col text-center">
                    <a href="{{ route('classroom.students.export', $courseId) }}" id="export-excel" class="btn btn-outline-primary">Export to Excel</a>
                </div>
               @include('classroom.tables.students')
            </div>
        </div>
    </div>
@endsection
