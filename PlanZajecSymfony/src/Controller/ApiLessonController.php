<?php

declare(strict_types=1);

namespace App\Controller;
use App\Repository\GroupRepository;
use App\Repository\RoomRepository;
use App\Repository\StudentRepository;
use App\Repository\LessonRepository;
use App\Repository\SubjectRepository;
use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    public function __construct(StudentRepository $studentRepository,LessonRepository $lessonRepository,TeacherRepository $teacherRepository,SubjectRepository $subjectRepository,GroupRepository $groupRepository,RoomRepository $roomRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->lessonRepository = $lessonRepository;
        $this->teacherRepository = $teacherRepository;
        $this->subjectRepository = $subjectRepository;
        $this->groupRepository = $groupRepository;
        $this->roomRepository = $roomRepository;
    }


    #[Route('/api/lessons', methods: ['GET'])]
    public function getApiLessons(string $teacher="" , string $subject="" , string $group="", string $room=""  , string $student="" , DateTime $start, DateTime $end ): JsonResponse
    {
        if($teacher!="")
        {
            $teacher="%".$teacher."%";
            $teacher=$this->teacherRepository->findTeacherByNameGetId($teacher);
        }
        if($subject!="")
        {
            $subject="%".$subject."%";
            $subject=$this->subjectRepository->findSubjectByNameGetID($subject);
        }
        if($group!="")
        {
            $group="%".$group."%";
            $group=$this->groupRepository->findGroupByNameGetId($group);
        }

        if($room!="")
        {
            $room="%".$room."%";
            $room=$this->roomRepository->findRoomByNameGetId($room);
        }

        if($student!="")
        {
            $student="%".$student."%";
            $group=$this->studentRepository->findGroupIB($student);
        }




        $data= $this->lessonRepository->findLessonAPI( $teacher, $subject, $group,$room, $start, $end);
        $data=json_encode($data);
        return new JsonResponse($data, Response::HTTP_OK);
    }


}
