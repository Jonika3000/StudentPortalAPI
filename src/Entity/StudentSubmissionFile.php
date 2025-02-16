<?php

namespace App\Entity;

use App\Repository\StudentSubmissionFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StudentSubmissionFileRepository::class)]
#[ORM\HasLifecycleCallbacks]
class StudentSubmissionFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['student_submission_read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Groups(['student_submission_read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['student_submission_read'])]
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
