<?php

namespace App\Entity;

use App\Enum\LessonForms;
use App\Enum\LessonStatuses;
use App\Repository\LessonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LessonRepository::class)]
class Lesson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    private ?int $hours = null;

    #[ORM\ManyToOne]
    private ?Teacher $workerId = null;

    #[ORM\ManyToOne]
    private ?Teacher $workerCoverId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $groupId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $roomId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subject $subjectId = null;

    #[ORM\Column(enumType: LessonForms::class)]
    private ?LessonForms $lessonForm = null;

    #[ORM\Column(enumType: LessonStatuses::class)]
    private ?LessonStatuses $lessonStatus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getHours(): ?int
    {
        return $this->hours;
    }

    public function setHours(int $hours): static
    {
        $this->hours = $hours;

        return $this;
    }

    public function getWorkerId(): ?Teacher
    {
        return $this->workerId;
    }

    public function setWorkerId(?Teacher $workerId): static
    {
        $this->workerId = $workerId;

        return $this;
    }

    public function getWorkerCoverId(): ?Teacher
    {
        return $this->workerCoverId;
    }

    public function setWorkerCoverId(?Teacher $workerCoverId): static
    {
        $this->workerCoverId = $workerCoverId;

        return $this;
    }

    public function getGroupId(): ?Group
    {
        return $this->groupId;
    }

    public function setGroupId(?Group $groupId): static
    {
        $this->groupId = $groupId;

        return $this;
    }

    public function getRoomId(): ?Room
    {
        return $this->roomId;
    }

    public function setRoomId(?Room $roomId): static
    {
        $this->roomId = $roomId;

        return $this;
    }

    public function getSubjectId(): ?Subject
    {
        return $this->subjectId;
    }

    public function setSubjectId(?Subject $subjectId): static
    {
        $this->subjectId = $subjectId;

        return $this;
    }

    public function getLessonForm(): ?LessonForms
    {
        return $this->lessonForm;
    }

    public function setLessonForm(LessonForms $lessonForm): static
    {
        $this->lessonForm = $lessonForm;

        return $this;
    }

    public function getLessonStatus(): ?LessonStatuses
    {
        return $this->lessonStatus;
    }

    public function setLessonStatus(LessonStatuses $lessonStatus): static
    {
        $this->lessonStatus = $lessonStatus;

        return $this;
    }
}
