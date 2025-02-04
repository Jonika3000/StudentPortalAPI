<?php

namespace App\Factory;

use App\Entity\Classroom;
use App\Entity\Lesson;
use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;

readonly class LessonFactory
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(Classroom $classroom, Subject $subject, array $teachers): Lesson
    {
        $lesson = new Lesson();
        $lesson->setClassroom($classroom);
        $lesson->setSubject($subject);

        foreach ($teachers as $teacher) {
            $lesson->addTeacher($teacher);
        }

        $this->entityManager->persist($lesson);
        $this->entityManager->flush();

        return $lesson;
    }
}
