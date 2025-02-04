<?php

namespace App\Factory;

use App\Entity\Classroom;
use Doctrine\ORM\EntityManagerInterface;

readonly class ClassroomFactory
{
    public function __construct(
        private EntityManagerInterface $entityManager,
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
