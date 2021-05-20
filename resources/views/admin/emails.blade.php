@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div id="card-header-emails" class="card-header">
                        <div class="align-self-center">
                            {{ __('Allowed emails') }}
                        </div>
                        <div>
                            <button class="btn btn-outline-success" onclick="openModal('create')">Add email</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">

                            @include('admin.tables.emails')

                            <!-- Pagination -->
                            <div class="pagination-center">
                                {{ $emails->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create or update modal -->
        @include('admin.modals.changing')

        <!-- Remove modal -->
        @include('admin.modals.remove')

    </div>
@endsection
