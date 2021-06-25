$(window).ready(function () {
    sessionStorage.clear();
    $('#students-next-page-token').val($('#students-first-page-token').val());
    $('#count-students').val(30);
});

$(window).scroll(function(){
    if ($(window).scrollTop() === $(document).height() - $(window).height()) {
        let courseId = $('#course-id').val();
        let nextPageToken;
        let previousPageToken;
        if (courseId) {
            nextPageToken = $('#students-next-page-token').val();
            previousPageToken = sessionStorage.getItem('studentsNextPageToken');
            if (nextPageToken && previousPageToken !== nextPageToken) {
                _getMoreStudents(courseId, nextPageToken);
                sessionStorage.setItem('studentsNextPageToken', nextPageToken);
            }
        } else {
            nextPageToken = $('#courses-next-page-token').val();
            previousPageToken = sessionStorage.getItem('coursesNextPageToken');
            if (nextPageToken && previousPageToken !== nextPageToken) {
                _getMoreCourses(nextPageToken);
                sessionStorage.setItem('coursesNextPageToken', nextPageToken);
            }
        }
    }
});

function _getMoreStudents(courseId, nextPageToken) {
    $.ajax({
        url: `/classroom/students/${ courseId }/${ nextPageToken }`,
        type: "GET",
        headers: {
            'Content-Type': 'application/json'
        },
        beforeSend: function() {
            $('#loading').show();
        },
        complete: function() {
            $('#loading').hide();
        },
        success: function(response) {
            $('#students-next-page-token').val(response.nextPageToken);
            _appendStudents(response.students);
        },
        error: function() {}
    });
}

function _appendStudents(students) {
    let countStudents = $('#count-students').val();
    for (let student of students) {
        countStudents++;
        $('#students-table').append('<tr>' +
            '<th class="counter vertical-center d-none d-xl-table-cell" scope="row">'+ countStudents +'</th>' +
            '<td class="vertical-center d-none d-xl-table-cell">' +
            '<img src="'+ student.profile.photoUrl +'" class="avatar"></td>' +
            '<td class="vertical-center is-breakable">'+ student.profile.name.fullName +' </td>' +
            '<td class="vertical-center is-breakable">'+ student.profile.emailAddress +' </td>' +
            '</tr>');
    }
    $('#count-students').val(countStudents);
}

function _getMoreCourses(nextPageToken) {
    $.ajax({
        url: `/classroom/courses/${ nextPageToken }`,
        type: "GET",
        headers: {
            'Content-Type': 'application/json'
        },
        beforeSend: function() {
            $('#loading').show();
        },
        complete: function() {
            $('#loading').hide();
        },
        success: function(response) {
            $('#students-next-page-token').val(response.nextPageToken);
            _appendCourses(response.courses);
        },
        error: function() {}
    });
}

function _appendCourses(courses) {
    let countCourses = $('#count-courses').val();
    for (let course of courses) {
        countCourses++;
        $('#courses-table').append('<tr id="tr-'+ countCourses +'">' +
                '<th class="counter vertical-center d-none d-xl-table-cell" scope="row">'+ countCourses +'</th>' +
                '<td class="vertical-center is-breakable">'+ course.name +'</td>' +
                '<td class="vertical-center is-breakable">'+ course.descriptionHeading +'</td>' +
                '<td class="vertical-center d-none d-md-table-cell '+ course.courseState +'">'+ course.courseState +'</td>' +
                '<td class="vertical-center d-none d-md-table-cell">' +
                    '<a class="btn btn-outline-info" target="_blank" href="'+ course.alternateLink +'">Link</a>' +
                '</td>' +
                '<td class="vertical-center">' +
                    '<a class="btn btn-outline-success" href="/students/'+ course.id +'">Students</a>' +
                '</td>' +
            '</tr>');
    }
    $('#count-courses').val(countCourses);
}

