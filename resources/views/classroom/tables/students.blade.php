<table class="table" id="email-allowed-table">
    <thead>
    <tr>
        <th scope="col" class="d-none d-xl-table-cell">#</th>
        <th scope="col" class="">Name</th>
        <th scope="col" class="">Email</th>
    </tr>
    </thead>
    <tbody>
    @foreach($students as $index => $student)
        <tr>
            <th class="counter vertical-center d-none d-xl-table-cell" scope="row">{{ $index + 1 }}</th>
            <td class="vertical-center is-breakable">{{ $student->profile->name->fullName }}</td>
            <td class="vertical-center is-breakable">{{ $student->profile->emailAddress }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
