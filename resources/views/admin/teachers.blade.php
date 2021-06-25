@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Регистрирани преподаватели</div>
                    <div class="card-body">
                        <div class="table-responsive">

                            @include('admin.tables.teachers')

                            <!-- Pagination -->
                            <div class="pagination-center">
                                {{ $users->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
