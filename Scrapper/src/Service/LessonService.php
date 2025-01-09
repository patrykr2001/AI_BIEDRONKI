<?php

namespace App\Service;

use App\Entity\Lesson;
use App\Repository\LessonRepository;

class LessonService
{
    private LessonRepository $lessonRepository;

    public function __construct(LessonRepository $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
    }

    /**
     * Saves a new Lesson record.
     *
     * @param Lesson $lesson
     */
    public function save(Lesson $lesson): void
    {
        $this->lessonRepository->save($lesson);
    }

    /**
     * Saves an array of Lesson records.
     *
     * @param Lesson[] $lessons
     */
    public function saveAll(array $lessons): void
    {
        foreach ($lessons as $lesson) {
            $this->lessonRepository->save($lesson);
        }
    }

    /**
     * Saves new Lesson records if they do not already exist.
     *
     * @param Lesson[] $lessons
     */
    public function saveNewLessons(array $lessons): void
    {
        foreach ($lessons as $lesson) {
            if ($this->lessonRepository->findLessonByName($lesson->getName()) === null) {
                $this->lessonRepository->save($lesson);
            }
        }
    }

    /**
     * Retrieves a Lesson by its ID.
     *
     * @param int $id
     * @return Lesson|null
     */
    public function getLessonById(int $id): ?Lesson
    {
        return $this->lessonRepository->find($id);
    }

    /**
     * Retrieves all Lessons.
     *
     * @return Lesson[]
     */
    public function getAllLessons(): array
    {
        return $this->lessonRepository->findAll();
    }
}