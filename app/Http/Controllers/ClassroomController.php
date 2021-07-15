<?php

namespace App\Http\Controllers;

use Brian2694\Toastr\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Exports\StudentsExport;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Google_Service_Classroom_Student;
use Illuminate\Contracts\View\Factory;
use App\Services\GoogleClassroomService;
use Google\Exception as GoogleException;
use App\Services\RetrieveClassroomResourcesService;
use Illuminate\Contracts\Foundation\Application;
use Google_Service_Classroom_ListStudentsResponse;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use PhpOffice\PhpSpreadsheet\Exception as PhpOfficeException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpOfficeWriterException;

class ClassroomController extends Controller
{
    /**
     * Display a listing of courses on the first page, maximum 30.
     *
     * @param Request $request
     * @return Application|Factory|View
     * @throws GoogleException
     */
    public function firstPageCourses(Request $request)
    {
        $service = new GoogleClassroomService($request);
        $optParams = [
            'fields' => 'courses(id,name,descriptionHeading,courseState,teacherFolder/alternateLink)'
        ];
        $data = $service
            ->getClassroom()
            ->courses
            ->listCourses($optParams);
        $nextPageToken = $data->nextPageToken;
        $courses = $data->getCourses();

        return view('classroom.courses', compact(['nextPageToken', 'courses']));
    }

    /**
     * Retrieve courses from the next page, maximum 30.
     *
     * @param Request $request
     * @param string $nextPageToken
     * @return JsonResponse
     * @throws GoogleException
     */
    public function nextPageCourses(Request $request, string $nextPageToken): JsonResponse
    {
        $service = new GoogleClassroomService($request);
        $optParams = [
            'pageToken' => $nextPageToken,
            'fields' => 'nextPageToken,courses(id,name,descriptionHeading,courseState,teacherFolder/alternateLink)'
        ];
        $students = $service
            ->getClassroom()
            ->courses
            ->listCourses($optParams);

        return response()->json($students);
    }

    /**
     * Display a listing of students on the first page, maximum 30.
     *
     * @param Request $request
     * @param string $courseId
     * @return Application|Factory|View
     * @throws GoogleException
     */
    public function firstPageStudents(Request $request, string $courseId)
    {
        $service = new GoogleClassroomService($request);
        $optParams = [
            'fields' => 'nextPageToken,students(profile/name/fullName,profile/emailAddress,profile/photoUrl)'
        ];
        $data = $service->getClassroom()
            ->courses_students
            ->listCoursesStudents($courseId, $optParams);

        $students = $data->getStudents();
        $nextPageToken = $data->nextPageToken;
        $this->fixAvatarUrl($students);

        return view('classroom.students', compact(['nextPageToken', 'students', 'courseId']));
    }

    /**
     * Retrieve students from the next page, maximum 30.
     *
     * @param Request $request
     * @param string $courseId
     * @param string $nextPageToken
     * @return JsonResponse
     * @throws GoogleException
     */
    public function nextPageStudents(Request $request, string $courseId, string $nextPageToken): JsonResponse
    {
        $service = new GoogleClassroomService($request);
        $optParams = [
            'pageToken' => $nextPageToken,
            'fields' => 'nextPageToken,students(profile/name/fullName,profile/emailAddress,profile/photoUrl)'
        ];
        $students = $service
            ->getClassroom()
            ->courses_students
            ->listCoursesStudents($courseId, $optParams);

        $this->fixAvatarUrl($students);
        return response()->json($students);
    }

    /**
     * Export an Excel file with students and their assignments.
     *
     * @param Request $request
     * @param string $courseId
     * @return BinaryFileResponse|RedirectResponse
     * @throws GoogleException
     */
    public function export(Request $request, string $courseId)
    {
        $classroomService = new GoogleClassroomService($request);
        $retrieveService = new RetrieveClassroomResourcesService($classroomService, $courseId);

        $students = $retrieveService->getRoster();
        try {
            return Excel::download(new StudentsExport($students), "students-$courseId.xlsx");
        } catch (PhpOfficeWriterException | PhpOfficeException $exception) {
            Toastr::error('Нещо се обърка при експорта!');
            return redirect()->route('classroom.students', [$courseId]);
        }
    }

    /**
     * If the student has a photo the service returns path without HTTPS protocol.
     * @param Google_Service_Classroom_Student[]|Google_Service_Classroom_ListStudentsResponse $students
     * @return void
     */
    private function fixAvatarUrl($students): void
    {
        foreach ($students as $student) {
            if ($student->profile->photoUrl[0] === '/') {
                $student->profile->photoUrl = 'https:' . $student->profile->photoUrl;
            }
        }
    }
}
