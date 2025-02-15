<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\StudentRepository;
use App\Shared\Response\Exception\Student\StudentNotFoundException;
use Doctrine\Common\Collections\Collection;

readonly class LessonService
{
    public function __construct(
        private StudentRepository $studentRepository,
    ) {
    }

    /**
     * @throws StudentNotFoundException
     */
    public function getLessonsByStudent(User $user): Collection
    {
        $student = $this->studentRepository->findOneBy(['associatedUser' => $user->getId()]);

        if (!$student) {
            throw new StudentNotFoundException();
        }

        $classroom = $student->getClassroom();

        return $classroom->getLessons();
    }
}
