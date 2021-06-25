<table class="table" id="students-table">
    <input type="hidden" id="course-id" value="{{ $courseId }}">
    <input type="hidden" id="students-next-page-token" value={{ $nextPageToken }}>
    <input type="hidden" id="students-first-page-token" value={{ $nextPageToken }}>
    <input type="hidden" id="count-students" value="{{ count($students) }}">
    <thead>
    <tr>
        <th scope="col" class="d-none d-xl-table-cell">#</th>
        <th scope="col" class="d-none d-xl-table-cell">Снимка</th>
        <th scope="col" class="">Име</th>
        <th scope="col" class="">Имейл</th>
    </tr>
    </thead>
    <tbody>
    @foreach($students as $index => $student)
        <tr>
            <th class="counter vertical-center d-none d-xl-table-cell" scope="row">{{ $index + 1 }}</th>
            <td class="vertical-center d-none d-xl-table-cell">
                <img class="avatar" src="{{ $student->profile->photoUrl }}" alt="">
            </td>
            <td class="vertical-center is-breakable">{{ $student->profile->name->fullName }}</td>
            <td class="vertical-center is-breakable">{{ $student->profile->emailAddress }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
