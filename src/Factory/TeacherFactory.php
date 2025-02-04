<?php

namespace App\Factory;

use App\Constants\UserRoles;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;

class TeacherFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserFactory            $userFactory,
    ) {
    }

    public function create(): Teacher
    {
        $user = $this->userFactory->create(UserRoles::TEACHER);

        $teacher = new Teacher();
        $teacher->setAssociatedUser($user);

        $this->entityManager->persist($user);
        $this->entityManager->persist($teacher);
        $this->entityManager->flush();

        return $teacher;
    }
}
