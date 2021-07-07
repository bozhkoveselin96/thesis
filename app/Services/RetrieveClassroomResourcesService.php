<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class RetrieveClassroomResourcesService
{
    private array $students;
    private array $courseWork;
    private array $studentSubmissions;

    /**
     * @param GoogleClassroomService $service
     * @param string $courseId
     */
    public function __construct(GoogleClassroomService $service, string $courseId)
    {
        $this->setTargetTopic($service, $courseId);
        $students = $this->getStudents($service, $courseId);
        $courseWork = $this->getCourseWork($service, $courseId);
        $studentSubmissions = $this->getStudentSubmissions($service, $courseId, $students, $courseWork);
        $this->setCourseWork($courseWork);
        $this->setStudents($students);
        $this->setStudentSubmission($studentSubmissions);
    }

    /**
     * Retrieve all students from current course.
     *
     * @param GoogleClassroomService $service
     * @param string $courseId
     * @return array
     */
    private function getStudents(GoogleClassroomService $service, string $courseId): array
    {
        // Runs once, then again until nextPageToken is missing in the response.
        $roster = [];
        // The optional arguments pageToken and pageSize can be independently omitted or included.
        // In general, 'pageToken' is essentially required for large collections.
        $optParams = [
            'pageSize' => config('services.google.classroom_max_user_per_page'),
            'fields' => 'nextPageToken,students(userId,profile/name/fullName,profile/emailAddress)'// Working with partial resources improves efficiency.
        ];
        do {
            // Get the next page of students for this course.
            $search = $service
                ->getClassroom()
                ->courses_students
                ->listCoursesStudents($courseId, $optParams);

            // Add this page's students to the local collection of students.
            // (Could do something else with them now, too.)
            if ($search->getStudents()) {
                $nextPageStudents = $search->getStudents();
                foreach ($nextPageStudents as $student) {
                    array_push($roster, $student);
                }
            }

            // Update the page for the request
            $optParams['pageToken'] = $search->nextPageToken;
        } while ($optParams['pageToken']);
        return $roster;
    }

    /**
     * Retrieve all courseWork from current course
     *
     * @param GoogleClassroomService $service
     * @param string $courseId
     * @return array
     */
    private function getCourseWork(GoogleClassroomService $service, string $courseId): array
    {
        $optParams = [
            'fields' => 'courseWork(userId,profile/name/fullName,profile/emailAddress)'
        ];
        $allCourseWork = $service
            ->getClassroom()
            ->courses_courseWork
            ->listCoursesCourseWork($courseId)
            ->getCourseWork();

        foreach ($allCourseWork as $id => $courseWork) {
            if ($courseWork->topicId !== Session::get('topicId')) {
                unset($allCourseWork[$id]);
            }
        }
        return $allCourseWork;
    }

    /**
     * Retrieve
     *
     * @param GoogleClassroomService $service
     * @param string $courseId
     * @param array $students
     * @param array $allCourseWork
     * @return array
     */
    public function getStudentSubmissions(GoogleClassroomService $service, string $courseId, array $students, array $allCourseWork)
    {
        $optParams = [
//            'states' => 'TURNED_IN',
            'fields' => 'studentSubmissions(submissionHistory)'
        ];

        $history = [];
        foreach ($allCourseWork as $courseWork) {
            $submissions = $service->getClassroom()
                ->courses_courseWork_studentSubmissions
                ->listCoursesCourseWorkStudentSubmissions($courseId, $courseWork->id, $optParams)
                ->getStudentSubmissions();
            foreach ($submissions[0]->getSubmissionHistory() as $submission) {
                $history[] = $submission->getStateHistory();
//                if ($submission->getStateHistory()->state === 'TURNED_IN') {
//                    $history['id'] = $courseWork->id;
//                    $history['date'] = $submission->getStateHistory()->stateTimestamp;
//                    break;
//                }
            }
        }
        dd($history);
    }

    /**
     * @param array $students
     */
    private function setStudents(array $students): void
    {
        $this->students = $students;
    }

    /**
     * @param array $courseWork
     */
    private function setCourseWork(array $courseWork): void
    {
        $this->courseWork = $courseWork;
    }

    /**
     * @param array $studentSubmissions
     */
    public function setStudentSubmission(array $studentSubmissions): void
    {
        $this->studentSubmissions = $studentSubmissions;
    }

    public function getRoster(): array
    {
        $allStudents = $this->students;
        $finalRoster = [];
        foreach ($this->courseWork as $currentCourseWork) {
            $dueDate = $currentCourseWork->dueDate;
            $currentDueDate = $dueDate->day . '/' . $dueDate->month . '/'  . $dueDate->year;
            foreach ($allStudents as $id => $currentStudent) {
                if ($currentCourseWork->topicId === Session::get('topicId') &&
                    $currentCourseWork->individualStudentsOptions &&
                    $currentCourseWork->individualStudentsOptions->studentIds[0] === $currentStudent->userId) {
                        $studentWithAssignment = new \stdClass();
                        $studentWithAssignment->number = null;
                        $studentWithAssignment->fullName = $currentStudent->profile->name->fullName;
                        if ($currentCourseWork->description) {
                            $studentAdditionalInfo = preg_split("/[\t]/", $currentCourseWork->description);
                            if (count($studentAdditionalInfo) === config('services.google.classroom_course_work_correct_description')) {
                                $studentWithAssignment->fullName = $studentAdditionalInfo[0];
                                $studentWithAssignment->studentNumber = $studentAdditionalInfo[2];
                                $studentWithAssignment->studentGroup = $studentAdditionalInfo[1];
                            } else {
                                $studentWithAssignment->studentNumber = '';
                                $studentWithAssignment->studentGroup = '';
                            }
                        }
                        $studentWithAssignment->emailAddress = $currentStudent->profile->emailAddress;
                        $studentWithAssignment->assignedTopic = $currentCourseWork->title;
                        $studentWithAssignment->dueDate = $currentDueDate;
                        $finalRoster[] = $studentWithAssignment;
                        unset($allStudents[$id]);
                        break;
                }
            }
        }
        $this->sortByStudentGroup($finalRoster);
        return $finalRoster;
    }

    /**
     * The service returns all the topics we extract with the method getCourseWork().
     * In the Excel file, we only require a specific topic, so we keep the ID we need.
     *
     * @param GoogleClassroomService $service
     * @param string $courseId
     * @return void
     */
    private function setTargetTopic(GoogleClassroomService $service, string $courseId): void
    {
        $topicRoster = $service->getClassroom()->courses_topics->listCoursesTopics($courseId)->getTopic();
        foreach ($topicRoster as $topic) {
            if ($topic->getName() === config('services.google.classroom_target_topic_name')) {
                Session::put('topicId', $topic->getTopicId());
                return;
            }
        }
    }

    /**
     * @param array $roster
     * @return void
     */
    private function sortByStudentGroup(array &$roster): void
    {
        usort($roster, fn($first, $second) => strcmp($first->studentGroup, $second->studentGroup));
    }
}
