<?php

namespace App\Entity;

use App\Enum\DataUpdateTypes;
use App\Repository\DataUpdateLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DataUpdateLogRepository::class)]
class DataUpdateLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updateDate = null;

    #[ORM\Column(enumType: DataUpdateTypes::class)]
    private ?DataUpdateTypes $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTimeInterface $updateDate): static
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getType(): ?DataUpdateTypes
    {
        return $this->type;
    }

    public function setType(DataUpdateTypes $type): static
    {
        $this->type = $type;

        return $this;
    }
}
