<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $TITLE = null;

    #[ORM\Column]
    private ?bool $Done = null;

    #[ORM\Column(length: 255)]
    private ?string $Owneremail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getTITLE(): ?string
    {
        return $this->TITLE;
    }

    public function setTITLE(string $TITLE): self
    {
        $this->TITLE = $TITLE;

        return $this;
    }

    public function isDone(): ?bool
    {
        return $this->Done;
    }

    public function setDone(bool $Done): self
    {
        $this->Done = $Done;

        return $this;
    }

    public function getOwneremail(): ?string
    {
        return $this->Owneremail;
    }

    public function setOwneremail(string $Owneremail): self
    {
        $this->Owneremail = $Owneremail;

        return $this;
    }
}
