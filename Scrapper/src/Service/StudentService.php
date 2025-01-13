<?php

namespace App\Service;

use App\Entity\Student;
use App\Repository\StudentRepository;

class StudentService
{
    private StudentRepository $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function getAllStudents(): array
    {
        return $this->studentRepository->findAllStudents();
    }

    public function getStudentByNumber(string $number): ?Student
    {
        return $this->studentRepository->findStudentByNumber($number);
    }

    public function save(Student $student): void
    {
        $this->studentRepository->saveStudent($student);
    }

    public function updateGroups(array $groups, string $number, bool $onlyNew = true): void
    {
        if ($onlyNew) {
            $this->studentRepository->updateStudentGroups($groups, $number);
        } else {
            $this->studentRepository->updateStudentGroupsAll($groups, $number);
        }
    }
}