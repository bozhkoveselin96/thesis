<table class="table" id="courses-table">
    <input type="hidden" id="courses-next-page-token" value={{ $nextPageToken }}>
    <input type="hidden" id="courses-first-page-token" value={{ $nextPageToken }}>
    <input type="hidden" id="count-courses" value="{{ count($courses) }}">
    <thead>
    <tr>
        <th scope="col" class="d-none d-xl-table-cell">#</th>
        <th scope="col" class="">Name</th>
        <th scope="col" class="">Description</th>
        <th scope="col" class="d-none d-md-table-cell">Course State</th>
        <th scope="col" class="d-none d-md-table-cell">Link to the course</th>
        <th scope="col" class="">Students</th>
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
                <a class="btn btn-outline-info btn-size" target="_blank" href="{{ $course->alternateLink }}">Link</a>
            </td>
            <td class="vertical-center">
                <a class="btn btn-outline-success btn-size"
                   href="{{ route('classroom.students', $course->id) }}">Students
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
