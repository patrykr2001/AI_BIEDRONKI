<?php

namespace App\Service;

use App\Entity\Lesson;
use App\Repository\LessonRepository;
use App\Utils\ProgressBarPrinter;

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
        $done = 0;
        $total = count($lessons);
        foreach ($lessons as $lesson) {
            try {
                $dbLesson = $this->lessonRepository->findLessonByTeacherSubjectGroupStartEnd($lesson->getWorkerId(),
                    $lesson->getSubjectId(), $lesson->getGroupId(), $lesson->getStartDate(), $lesson->getEndDate());
                if ($dbLesson === null) {
                    $this->lessonRepository->save($lesson);
                } else {
                    $this->lessonRepository->remove($dbLesson);
                    $this->lessonRepository->save($lesson);
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
            ProgressBarPrinter::printProgressBar(++$done, $total);
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