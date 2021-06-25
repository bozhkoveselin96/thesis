<table class="table">
    <thead>
    <tr>
        <th scope="col" class="d-none d-xl-table-cell">#</th>
        <th scope="col" class="d-none d-md-table-cell">Име</th>
        <th scope="col" class="">Имейл</th>
        <th scope="col" class="d-none d-xl-table-cell">Снимка</th>
        <th scope="col" class="d-none d-xl-table-cell">Статус</th>
        <th scope="col" class="">Достъп</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $index => $teacher)
        <tr>
            <th class="vertical-center d-none d-xl-table-cell" scope="row">{{ $index + $users->firstItem() }}</th>
            <td class="vertical-center d-none d-md-table-cell">{{ $teacher->name }}</td>
            @if(!$teacher->blocked)
                <td id="email-{{ $teacher->id }}" class="vertical-center is-breakable unblocked-email">{{ $teacher->email }}</td>
            @else
                <td id="email-{{ $teacher->id }}" class="vertical-center is-breakable blocked-email">{{ $teacher->email }}</td>
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
                            class="btn btn-outline-danger"
                            onclick="blockOrUnblock({{ $teacher->id }})"
                            value="block">БЛОКИРАЙ
                    </button>
                @else
                    <button id="current-value-{{ $teacher->id }}"
                            class="btn btn-outline-success"
                            onclick="blockOrUnblock({{ $teacher->id }})"
                            value="unblock">ОТБЛОКИРАЙ
                    </button>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
