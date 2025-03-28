<?php

namespace App\Factory;

use App\Constants\UserRoles;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

readonly class StudentFactory
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserFactory $userFactory,
    ) {
    }

    public function create(): Student
    {
        $user = $this->userFactory->create(UserRoles::STUDENT);

        $student = new Student();
        $student->setAssociatedUser($user);
        $student->setContactParent(Factory::create()->phoneNumber());

        $this->entityManager->persist($user);
        $this->entityManager->persist($student);
        $this->entityManager->flush();

        return $student;
    }
}
