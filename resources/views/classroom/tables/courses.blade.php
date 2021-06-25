<table class="table" id="courses-table">
    <input type="hidden" id="courses-next-page-token" value={{ $nextPageToken }}>
    <input type="hidden" id="courses-first-page-token" value={{ $nextPageToken }}>
    <input type="hidden" id="count-courses" value="{{ count($courses) }}">
    <thead>
    <tr>
        <th scope="col" class="d-none d-xl-table-cell">#</th>
        <th scope="col" class="">Име на курса</th>
        <th scope="col" class="">Описание</th>
        <th scope="col" class="d-none d-md-table-cell">Статус</th>
        <th scope="col" class="d-none d-md-table-cell">Линк</th>
        <th scope="col" class="">Студенти</th>
    </tr>
    </thead>
    <tbody>
    @foreach($courses as $index => $course)
        <tr id="tr-{{ $course->id }}">
            <th class="counter vertical-center d-none d-xl-table-cell" scope="row">{{ $index + 1 }}</th>
            <td class="vertical-center is-breakable">{{ $course->name }}</td>
            <td class="vertical-center is-breakable">{{ $course->descriptionHeading }}</td>
            <td class="vertical-center d-none d-md-table-cell {{ $course->courseState }}">{{ $course->courseState }}</td>
            <td class="vertical-center d-none d-md-table-cell">
                <a class="btn btn-outline-info" target="_blank" href="{{ $course->alternateLink }}">Линк</a>
            </td>
            <td class="vertical-center">
                <a class="btn btn-outline-success"
                   href="{{ route('classroom.students', $course->id) }}">Студенти
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
