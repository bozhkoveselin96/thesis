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

                            <!-- Email allowed table -->
                            <table class="table" id="email-allowed-table">
                                <thead>
                                <tr>
                                    <th scope="col" class="d-none d-xl-table-cell">#</th>
                                    <th scope="col" class="">Email</th>
                                    <th scope="col" class="" id="used-th">Used</th>
                                    <th scope="col" class="">Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($emails as $index => $item)
                                    <tr id="tr-{{ $item->id }}">
                                        <th class="counter vertical-center d-none d-xl-table-cell" scope="row">{{ $index + $emails->firstItem() }}</th>
                                        <td id="email-{{ $item->id }}" class="vertical-center is-breakable">{{ $item->email }}</td>
                                        <td class="vertical-center">
                                            @if (!$item->used)
                                                <i class="fa fa-lg fa-user-times"></i>
                                            @else
                                                <i class="fa fa-lg fa-user"></i>
                                            @endif
                                        </td>
                                        <td class="vertical-center">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-info dropdown-toggle" type="button" id="dropdown-menu-button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {{ __('SELECT') }}
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdown-menu-button">
                                                    <a href="#"
                                                       class="dropdown-item primary-color"
                                                       onclick="openModal('update', {{ $item->id }})">UPDATE
                                                    </a>
                                                    <a href="#"
                                                       id="email-remove-{{ $item->id }}"
                                                       class="dropdown-item danger-color"
                                                       onclick="openModal('remove', {{ $item->id }})">REMOVE
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

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
        <div class="modal fade" id="create-or-update-modal" tabindex="-1" role="dialog" aria-labelledby="update-modal-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="create-or-update-modal-label">Update email</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-label-group">
                            <input type="email" id="modal-input-email" class="form-control" placeholder="Email" />
                            <label for="modal-input-email">Email</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">CLOSE</button>
                        <button id="update-or-create-btn" type="button" class="btn btn-success"></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Remove modal -->
        <div class="modal fade" id="remove-modal" tabindex="-1" role="dialog" aria-labelledby="remove-modal-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="remove-email-modal-label">Remove teacher</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h3 id="remove-modal-body" class="is-breakable"></h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">CLOSE</button>
                        <button id="remove" type="button" class="btn btn-danger">REMOVE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
