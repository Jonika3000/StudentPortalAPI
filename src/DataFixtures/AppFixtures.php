<?php

namespace App\DataFixtures;

use App\Factory\ClassroomFactory;
use App\Factory\HomeworkFactory;
use App\Factory\LessonFactory;
use App\Factory\StudentFactory;
use App\Factory\SubjectFactory;
use App\Factory\TeacherFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly TeacherFactory $teacherFactory,
        private readonly StudentFactory $studentFactory,
        private readonly ClassroomFactory $classroomFactory,
        private readonly LessonFactory $lessonFactory,
        private readonly SubjectFactory $subjectFactory,
        private readonly HomeworkFactory $homeworkFactory,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $teachers = [];
        $students = [];
        $classrooms = [];
        $lessons = [];

        for ($i = 0; $i < 10; ++$i) {
            $teachers[] = $this->teacherFactory->create();
            $students[] = $this->studentFactory->create();
        }

        for ($i = 0; $i < 3; ++$i) {
            $classrooms[] = $this->classroomFactory->create(
                array_slice($students, $i * 3, 3)
            );
        }

        $subjects = [];
        for ($i = 0; $i < 5; ++$i) {
            $subjects[] = $this->subjectFactory->create();
        }

        foreach ($classrooms as $classroom) {
            foreach ($subjects as $subject) {
                $teachersForLesson = array_slice($teachers, 0, 2);
                $lesson = $this->lessonFactory->create($classroom, $subject, $teachersForLesson);
                $lessons[] = $lesson;
            }
        }

        foreach ($lessons as $lesson) {
            $this->homeworkFactory->create($lesson->getTeachers()[0], $lesson);
        }

        $manager->flush();
    }
}
