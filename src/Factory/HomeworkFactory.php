<?php

namespace App\Factory;

use App\Entity\Homework;
use App\Entity\Lesson;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

class HomeworkFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function create(Teacher $teacher, Lesson $lesson): Homework
    {
        $faker = Factory::create();

        $homework = new Homework();
        $homework->setTeacher($teacher);
        $homework->setLesson($lesson);
        $homework->setDescription($faker->sentence(10));
        $homework->setDeadline($faker->dateTimeBetween('now', '+1 month'));

        $this->entityManager->persist($homework);
        $this->entityManager->flush();

        return $homework;
    }
}
