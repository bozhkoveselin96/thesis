<table class="table" id="email-allowed-table">
    <thead>
    <tr>
        <th scope="col" class="d-none d-xl-table-cell">#</th>
        <th scope="col" class="">Имейл</th>
        <th scope="col" class="" id="used-th">Използван</th>
        <th scope="col" class="">Опции</th>
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
                    <i class="fa fa-lg fa-user-check"></i>
                @endif
            </td>
            <td class="vertical-center">
                <div class="dropdown">
                    <button class="btn btn-outline-info dropdown-toggle" type="button" id="dropdown-menu-button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        ИЗБЕРИ
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdown-menu-button">
                        <a href="#"
                           class="dropdown-item primary-color"
                           onclick="openModal('update', {{ $item->id }})">ПРОМЕНИ
                        </a>
                        <a href="#"
                           id="email-remove-{{ $item->id }}"
                           class="dropdown-item danger-color"
                           onclick="openModal('remove', {{ $item->id }})">ИЗТРИЙ
                        </a>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
