<?php

namespace App\Services;

use App\Entity\Classroom;
use App\Entity\User;
use App\Repository\StudentRepository;

class ClassroomService
{
    public function __construct(
        private readonly StudentRepository $studentRepository,
    ) {
    }

    public function getClassroomByStudent(User $user): ?Classroom
    {
        $student = $this->studentRepository->findOneBy(['associatedUser' => $user->getId()]);

        return $student->getClassroom();
    }

    public function getClassroomByTeacher(User $user): ?Classroom
    {
        $student = $this->studentRepository->findOneBy(['associatedUser' => $user->getId()]);

        return $student->getClassroom();
    }
}
