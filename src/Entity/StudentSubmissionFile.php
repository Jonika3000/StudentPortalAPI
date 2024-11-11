<?php

namespace App\Entity;

use App\Repository\StudentSubmissionFileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentSubmissionFileRepository::class)]
class StudentSubmissionFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\ManyToOne(inversedBy: 'studentSubmissionFiles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?StudentSubmission $studentSubmission = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getStudentSubmission(): ?StudentSubmission
    {
        return $this->studentSubmission;
    }

    public function setStudentSubmission(?StudentSubmission $studentSubmission): static
    {
        $this->studentSubmission = $studentSubmission;

        return $this;
    }
}