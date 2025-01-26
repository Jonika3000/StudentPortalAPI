<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\StudentRepository;
use Doctrine\Common\Collections\Collection;

readonly class LessonService
{
    public function __construct(
        private StudentRepository $studentRepository,
    ) {
    }

    public function getLessonsByStudent(User $user): Collection
    {
        $student = $this->studentRepository->findOneBy(['associatedUser' => $user->getId()]);

        $classroom = $student->getClassroom();

        return $classroom->getLessons();
    }
}
