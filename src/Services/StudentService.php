<?php

namespace App\Services;

use App\Entity\Student;
use App\Entity\User;
use App\Repository\StudentRepository;
use App\Shared\Response\Exception\Student\StudentNotFoundException;

readonly class StudentService
{
    public function __construct(
        private StudentRepository $studentRepository,
    ) {
    }

    /**
     * @throws StudentNotFoundException
     */
    public function getStudentByUser(User $user): Student
    {
        $student = $this->studentRepository->findOneBy(['associatedUser' => $user->getId()]);

        if (empty($student)) {
            throw new StudentNotFoundException();
        }

        return $student;
    }
}
