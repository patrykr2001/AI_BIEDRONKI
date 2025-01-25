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
use Psr\Log\LoggerInterface;
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
    private LoggerInterface $logger;

    public function __construct(StudentRepository $studentRepository, LessonRepository $lessonRepository,
                                TeacherRepository $teacherRepository, SubjectRepository $subjectRepository,
                                GroupRepository   $groupRepository, RoomRepository $roomRepository, LoggerInterface $logger)
    {
        $this->studentRepository = $studentRepository;
        $this->lessonRepository = $lessonRepository;
        $this->teacherRepository = $teacherRepository;
        $this->subjectRepository = $subjectRepository;
        $this->groupRepository = $groupRepository;
        $this->roomRepository = $roomRepository;
        $this->logger = $logger;
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
        $teacher = $request->query->get('Teacher', '');
        $subject = $request->query->get('Subject', '');
        $group = $request->query->get('Group', '');
        $room = $request->query->get('Room', '');
        $student = $request->query->get('Student', '');

        if ($teacher == "" && $subject == "" && $group == "" && $room == "" && $student == "") {
            return new JsonResponse("[]", Response::HTTP_BAD_REQUEST);
        }

        if ($teacher != "") {
            $this->logger->info("Teacher: " . $teacher);
            $teacher = $this->teacherRepository->findTeacherByNameGetId($teacher) ?? "";
        }
        if ($subject != "") {
            $subject = $this->subjectRepository->findSubjectByNameGetID($subject) ?? "";
        }
        if ($group != "") {
            $group = $this->groupRepository->findGroupByNameGetId($group) ?? "";
        }

        if ($room != "") {
            $room = $this->roomRepository->findRoomByNameGetId($room) ?? "";
        }

        if ($student != "") {
            $group = $this->studentRepository->findStudentByNumber($student)->getGroupId()->toArray() ?? "";
        }

        if ($teacher == "" && $subject == "" && $group == "" && $room == "" && $student == "") {
            return new JsonResponse("[]", Response::HTTP_BAD_REQUEST);
        }

        $data = $this->lessonRepository->findLessonAPI($teacher, $subject, $group == "" ? [] : $group, $room, $start, $end);

        $json = "[";

        foreach ($data as $lesson) {
            $str = "{";
            $str .= "\"id\": " . $lesson->getId() . ",";
            $str .= "\"startDate\": \"" . $lesson->getStartDate()->format('Y-m-d') . 'T' . $lesson->getStartDate()->format('H:i') . "\",";
            $str .= "\"endDate\": \"" . $lesson->getEndDate()->format('Y-m-d') . 'T' . $lesson->getEndDate()->format('H:i') . "\",";
            $str .= "\"hours\": " . $lesson->getHours() . ",";
            $str .= "\"worker\": \"" . $lesson->getWorkerId()->getName() . "\",";
            if ($lesson->getWorkerCoverId() != null)
                $str .= "\"workerCover\": \"" . $lesson->getWorkerCoverId()->getName() . "\",";
            else
                $str .= "\"workerCover\": \"\",";
            if ($lesson->getGroupId() != null)
                $str .= "\"group\": \"" . $lesson->getGroupId()->getName() . "\",";
            else
                $str .= "\"group\": \"\",";
            if ($lesson->getRoomId() != null)
                $str .= "\"room\": \"" . $lesson->getRoomId()->getName() . "\",";
            else
                $str .= "\"room\": \"\",";
            if ($lesson->getSubjectId() != null)
                $str .= "\"subject\": \"" . $lesson->getSubjectId()->getName() . "\",";
            else
                $str .= "\"subject\": \"\",";
            if ($lesson->getLessonForm() != null)
                $str .= "\"lessonForm\": \"" . $lesson->getLessonForm()->value . "\",";
            else
                $str .= "\"lessonForm\": \"\",";
            $str .= "\"lessonStatus\": \"" . $lesson->getLessonStatus()->value . "\"";
            $str .= "},";
            $json .= $str;
        }

        $json = substr($json, 0, -1);
        $json .= "]";

        return new JsonResponse($json, Response::HTTP_OK, ['content-type' => 'application/json'], true);
    }
}
