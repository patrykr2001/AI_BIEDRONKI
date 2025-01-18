<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
class ApiLesson
{
    private ?int $id = null;
    private ?\DateTimeInterface $startDate = null;
    private ?\DateTimeInterface $endDate = null;
    private ?float $hours = null;
    private ?string $worker = null;
    private ?string $workerCover = null;
    private ?string $group = null;
    private ?string $room = null;
    private ?string $subject = null;
    private ?string $lessonForm = null;
    private ?string $lessonStatus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getHours(): ?float
    {
        return $this->hours;
    }

    public function setHours(?float $hours): void
    {
        $this->hours = $hours;
    }

    public function getWorker(): ?string
    {
        return $this->worker;
    }

    public function setWorker(?string $worker): void
    {
        $this->worker = $worker;
    }

    public function getWorkerCover(): ?string
    {
        return $this->workerCover;
    }

    public function setWorkerCover(?string $workerCover): void
    {
        $this->workerCover = $workerCover;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function setGroup(?string $group): void
    {
        $this->group = $group;
    }

    public function getRoom(): ?string
    {
        return $this->room;
    }

    public function setRoom(?string $room): void
    {
        $this->room = $room;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    public function getLessonForm(): ?string
    {
        return $this->lessonForm;
    }

    public function setLessonForm(?string $lessonForm): void
    {
        $this->lessonForm = $lessonForm;
    }

    public function getLessonStatus(): ?string
    {
        return $this->lessonStatus;
    }

    public function setLessonStatus(?string $lessonStatus): void
    {
        $this->lessonStatus = $lessonStatus;
    }


}