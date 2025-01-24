<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\GroupRepository;
use App\Repository\LessonRepository;
use App\Repository\RoomRepository;
use App\Repository\StudentRepository;
use App\Repository\SubjectRepository;
use App\Repository\TeacherRepository;
use DateMalformedStringException;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class ApiLessonController extends AbstractController
{
    private StudentRepository $studentRepository;
    private LessonRepository $lessonRepository;
    private TeacherRepository $teacherRepository;
    private SubjectRepository $subjectRepository;
    private GroupRepository $groupRepository;
    private RoomRepository $roomRepository;

    public function __construct(StudentRepository $studentRepository, LessonRepository $lessonRepository, TeacherRepository $teacherRepository, SubjectRepository $subjectRepository, GroupRepository $groupRepository, RoomRepository $roomRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->lessonRepository = $lessonRepository;
        $this->teacherRepository = $teacherRepository;
        $this->subjectRepository = $subjectRepository;
        $this->groupRepository = $groupRepository;
        $this->roomRepository = $roomRepository;
    }

    #[Route('/api/lessons', methods: ['GET'])]
    public function getApiLessons(Request $request): JsonResponse
    {
        try {
            $start = new DateTime($request->query->get('startDate'));
        } catch (DateMalformedStringException $e) {
            return new JsonResponse("Invalid start date", Response::HTTP_BAD_REQUEST);
        }
        try {
            $end = new DateTime($request->query->get('endDate'));
        } catch (DateMalformedStringException $e) {
            return new JsonResponse("Invalid end date", Response::HTTP_BAD_REQUEST);
        }
        $teacher = $request->query->get('teacher', '');
        $subject = $request->query->get('subject', '');
        $group = $request->query->get('group', '');
        $room = $request->query->get('room', '');
        $student = $request->query->get('student', '');

        if ($teacher != "") {
            $teacher = "%" . $teacher . "%";
            $teacher = $this->teacherRepository->findTeacherByNameGetId($teacher);
        }
        if ($subject != "") {
            $subject = "%" . $subject . "%";
            $subject = $this->subjectRepository->findSubjectByNameGetID($subject);
        }
        if ($group != "") {
            $group = "%" . $group . "%";
            $group = $this->groupRepository->findGroupByNameGetId($group);
        }

        if ($room != "") {
            $room = "%" . $room . "%";
            $room = $this->roomRepository->findRoomByNameGetId($room);
        }

        if ($student != "") {
            $student = "%" . $student . "%";
            $group = $this->studentRepository->findGroupIB($student);
        }

        $data = $this->lessonRepository->findLessonAPI($teacher, $subject, $group, $room, $start, $end);
        $data = json_encode($data);
        return new JsonResponse($data, Response::HTTP_OK);
    }
}
