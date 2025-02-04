<?php

namespace App\Factory;

use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

readonly class SubjectFactory
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(): Subject
    {
        $faker = Factory::create();

        $subject = new Subject();
        $subject->setName($faker->word());
        $subject->setDescription($faker->sentence());
        $subject->setImagePath($faker->imageUrl());

        $this->entityManager->persist($subject);
        $this->entityManager->flush();

        return $subject;
    }
}
