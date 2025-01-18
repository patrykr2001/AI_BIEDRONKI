<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
class ApiGroup
{
    private ?int $id = null;

    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
