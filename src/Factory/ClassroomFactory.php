<?php

namespace App\Factory;

use App\Entity\Classroom;
use Doctrine\ORM\EntityManagerInterface;

class ClassroomFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function create(array $students): Classroom
    {
        $classroom = new Classroom();

        foreach ($students as $student) {
            $classroom->addStudent($student);
        }

        $this->entityManager->persist($classroom);
        $this->entityManager->flush();

        return $classroom;
    }
}
