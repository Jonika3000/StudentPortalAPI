<?php

namespace App\Services;

use App\Entity\Teacher;
use App\Entity\User;
use App\Repository\TeacherRepository;
use App\Shared\Response\Exception\Teacher\TeacherNotFoundException;

readonly class TeacherService
{
    public function __construct(
        private TeacherRepository $teacherRepository,
    ) {
    }

    /**
     * @throws TeacherNotFoundException
     */
    public function getTeacherByAssociatedUser(User $user): Teacher
    {
        $teacher = $this->teacherRepository->findOneBy(['associatedUser' => $user->getId()]);
        if (!$teacher) {
            throw new TeacherNotFoundException();
        }

        return $teacher;
    }
}
