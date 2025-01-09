<?php

namespace App\Service;

use App\Entity\Subject;
use App\Repository\SubjectRepository;

class SubjectService
{
    private SubjectRepository $subjectRepository;

    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function getAllSubjects(): array
    {
        return $this->subjectRepository->findAllSubjects();
    }

    public function getSubjectById(int $id): ?Subject
    {
        return $this->subjectRepository->findSubjectById($id);
    }

    public function getSubjectByName(string $name): ?Subject
    {
        return $this->subjectRepository->findSubjectByName($name);
    }

    public function saveSubject(Subject $subject): void
    {
        $this->subjectRepository->saveSubject($subject);
    }

    public function saveSubjects(array $subjects): void
    {
        $this->subjectRepository->saveSubjects($subjects);
    }

    public function saveNewSubjects(array $subjects): void
    {
        foreach ($subjects as $subject) {
            if ($this->subjectRepository->findSubjectByName($subject->getName()) === null) {
                $this->subjectRepository->saveSubject($subject);
            }
        }
    }
}