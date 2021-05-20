<?php
namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Services\GoogleClassroomService;
use Google\Exception as GoogleException;
use Maatwebsite\Excel\Concerns\FromView;

class StudentsExportView implements FromView
{
    private array $students;

    /**
     * @param Request $request
     * @param string $courseId
     * @throws GoogleException
     */
    public function __construct(Request $request, string $courseId)
    {
        $students = $this->getStudents($request, $courseId);
        $this->setStudents($students);
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('classroom.tables.students', [
            'students' => $this->students
        ]);
    }

    /**
     * Retrieve students in the current course from Classroom.
     *
     * @param Request $request
     * @param string $courseId
     * @return array
     * @throws GoogleException
     */
    private function getStudents(Request $request, string $courseId): array
    {
        $service = new GoogleClassroomService($request);
        return $service
            ->getClassroom()
            ->courses_students
            ->listCoursesStudents($courseId)
            ->getStudents();
    }

    /**
     * @param array $students
     */
    private function setStudents(array $students): void
    {
        $this->students = $students;
    }
}
