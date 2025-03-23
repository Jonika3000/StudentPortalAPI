<?php

namespace App\Entity;

use App\Repository\HomeworkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HomeworkRepository::class)]
class Homework
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'homework')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Teacher $teacher = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'homework')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lesson $lesson = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\Type(type: \DateTimeInterface::class)]
    private ?\DateTimeInterface $deadline = null;

    /**
     * @var Collection<int, HomeworkFile>
     */
    #[ORM\OneToMany(targetEntity: HomeworkFile::class, mappedBy: 'homework', cascade: ['remove'])]
    private Collection $homeworkFiles;

    /**
     * @var Collection<int, StudentSubmission>
     */
    #[ORM\OneToMany(targetEntity: StudentSubmission::class, mappedBy: 'homework', cascade: ['remove'])]
    private Collection $studentSubmissions;

    public function __construct()
    {
        $this->homeworkFiles = new ArrayCollection();
        $this->studentSubmissions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLesson(?Lesson $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTimeInterface $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * @return Collection<int, HomeworkFile>
     */
    public function getHomeworkFiles(): Collection
    {
        return $this->homeworkFiles;
    }

    public function addHomeworkFile(HomeworkFile $homeworkFile): static
    {
        if (!$this->homeworkFiles->contains($homeworkFile)) {
            $this->homeworkFiles->add($homeworkFile);
            $homeworkFile->setHomework($this);
        }

        return $this;
    }

    public function removeHomeworkFile(HomeworkFile $homeworkFile): static
    {
        if ($this->homeworkFiles->removeElement($homeworkFile)) {
            // set the owning side to null (unless already changed)
            if ($homeworkFile->getHomework() === $this) {
                $homeworkFile->setHomework(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StudentSubmission>
     */
    public function getStudentSubmissions(): Collection
    {
        return $this->studentSubmissions;
    }

    public function addStudentSubmission(StudentSubmission $studentSubmission): static
    {
        if (!$this->studentSubmissions->contains($studentSubmission)) {
            $this->studentSubmissions->add($studentSubmission);
            $studentSubmission->setHomework($this);
        }

        return $this;
    }

    public function removeStudentSubmission(StudentSubmission $studentSubmission): static
    {
        if ($this->studentSubmissions->removeElement($studentSubmission)) {
            // set the owning side to null (unless already changed)
            if ($studentSubmission->getHomework() === $this) {
                $studentSubmission->setHomework(null);
            }
        }

        return $this;
    }
}
