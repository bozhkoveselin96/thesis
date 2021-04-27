@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Registered teachers') }}</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col" class="d-none d-xl-table-cell">#</th>
                                    <th scope="col" class="d-none d-md-table-cell">Name</th>
                                    <th scope="col" class="">Email</th>
                                    <th scope="col" class="d-none d-xl-table-cell">Avatar</th>
                                    <th scope="col" class="d-none d-xl-table-cell">Status</th>
                                    <th scope="col" class="">Access</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $index => $teacher)
                                    <tr>
                                        <th class="vertical-center d-none d-xl-table-cell" scope="row">{{ $index + $users->firstItem() }}</th>
                                        <td class="vertical-center d-none d-md-table-cell">{{ $teacher->name }}</td>
                                        @if(!$teacher->blocked)
                                            <td id="email-{{ $teacher->id }}" class="vertical-center is-breakable approved-email">{{ $teacher->email }}</td>
                                        @else
                                            <td class="vertical-center is-breakable blocked-email">{{ $teacher->email }}</td>
                                        @endif
                                        <td class="vertical-center d-none d-xl-table-cell"><img class="avatar" src="{{ $teacher->avatar }}" alt=""></td>
                                        <td class="vertical-center d-none d-xl-table-cell">
                                            @if(!$teacher->blocked)
                                                <i id="access-status-{{ $teacher->id }}" class="fa fa-lg fa-check-circle"></i>
                                            @else
                                                <i id="access-status-{{ $teacher->id }}" class="fa fa-lg fa-times-circle"></i>
                                            @endif
                                        </td>
                                        <td class="vertical-center">
                                            @if(!$teacher->blocked)
                                                <button id="current-value-{{ $teacher->id }}"
                                                        class="btn btn-outline-danger btn-size"
                                                        onclick="blockOrUnblock({{ $teacher->id }})"
                                                        value="block">BLOCK
                                                </button>
                                            @else
                                                <button id="current-value-{{ $teacher->id }}"
                                                        class="btn btn-outline-danger btn-size"
                                                        onclick="blockOrUnblock({{ $teacher->id }})"
                                                        value="unblock">UNBLOCK
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

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
