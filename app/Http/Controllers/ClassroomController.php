<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\StudentsExportView;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\GoogleClassroomService;
use Google\Exception as GoogleException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the courses.
     *
     * @param Request $request
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
     * Display a listing of the students.
     *
     * @param Request $request
     * @param string $courseId
     * @throws GoogleException
     */
    public function students(Request $request, string $courseId) {
        $service = new GoogleClassroomService($request);
        $students = $service
            ->getClassroom()
            ->courses_students
            ->listCoursesStudents($courseId)
            ->getStudents();
        return view('classroom.students', compact(['students', 'courseId']));
    }

    /**
     *
     *
     * @param Request $request
     * @param string $courseId
     * @return BinaryFileResponse
     * @throws GoogleException
     */
    public function export(Request $request, string $courseId): BinaryFileResponse
    {
        return Excel::download(new StudentsExportView($request, $courseId), 'students.xlsx');
    }
}
