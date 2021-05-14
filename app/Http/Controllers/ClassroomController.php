<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleClassroomService;
use Google\Exception as GoogleException;
use App\Http\Requests\ShowStudentsRequest;
use Illuminate\Support\Facades\Session;

class ClassroomController extends Controller
{
    /**
     *
     *
     * @throws GoogleException
     */
    public function courses(Request $request) {
        $service = new GoogleClassroomService($request);
        $courses = $service
            ->getClassroom()
            ->courses
            ->listCourses()
            ->getCourses();
        return view('classroom.courses', compact('courses'));
    }

    /**
     *
     *
     * @throws GoogleException
     */
    public function students(Request $request, string $courseId) {
        $service = new GoogleClassroomService($request);
        $students = $service
            ->getClassroom()
            ->courses_students
            ->listCoursesStudents($courseId)
            ->getStudents();
        return view('classroom.students', compact('students'));
    }
}
