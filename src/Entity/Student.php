<?php

namespace App\Entity;

use App\Constants\UserRoles;
use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $associatedUser = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $contactParent = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotNull]
    private ?Classroom $classRoom = null;

    public function __construct()
    {
        $this->associatedUser?->setRoles([UserRoles::USER, UserRoles::STUDENT]);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAssociatedUser(): ?User
    {
        return $this->associatedUser;
    }

    public function setAssociatedUser(User $associatedUser): static
    {
        $this->associatedUser = $associatedUser;

        return $this;
    }

    public function getContactParent(): ?string
    {
        return $this->contactParent;
    }

    public function setContactParent(?string $contactParent): static
    {
        $this->contactParent = $contactParent;

        return $this;
    }

    public function getClassRoom(): ?Classroom
    {
        return $this->classRoom;
    }

    public function setClassRoom(?Classroom $classRoom): static
    {
        $this->classRoom = $classRoom;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getAssociatedUser()->getEmail();
    }
}
