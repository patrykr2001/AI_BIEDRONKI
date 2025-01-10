<?php

namespace App\Service;

use App\Entity\Teacher;
use App\Repository\TeacherRepository;
use App\Utils\ProgressBarPrinter;

class TeacherService
{
    private TeacherRepository $teacherRepository;

    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    public function getAllTeachers(): array
    {
        return $this->teacherRepository->findAllTeachers();
    }

    public function getTeacherById(int $id): ?Teacher
    {
        return $this->teacherRepository->findTeacherById($id);
    }

    public function getTeacherByName(string $name): ?Teacher
    {
        return $this->teacherRepository->findTeacherByName($name);
    }

    public function saveTeacher(Teacher $teacher): void
    {
        $this->teacherRepository->saveTeacher($teacher);
    }

    public function saveTeachers(array $teachers): void
    {
        $this->teacherRepository->saveTeachers($teachers);
    }

    public function saveNewTeachers(array $teachers): void
    {
        $done = 0;
        $total = count($teachers);
        foreach ($teachers as $teacher) {
            if ($this->teacherRepository->findTeacherByName($teacher->getName()) === null) {
                $this->teacherRepository->saveTeacher($teacher);
            }
            ProgressBarPrinter::printProgressBar(++$done, $total, step: 10);
        }
    }
}