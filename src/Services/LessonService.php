<?php

namespace App\Services;

use App\Constants\UserRoles;
use App\Entity\Lesson;
use App\Entity\User;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use App\Shared\Response\Exception\Student\StudentNotFoundException;
use App\Shared\Response\Exception\Teacher\TeacherNotFoundException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

readonly class LessonService
{
    public function __construct(
        private StudentRepository $studentRepository,
        private TeacherRepository $teacherRepository,
    ) {
    }

    /**
     * @throws StudentNotFoundException
     * @throws TeacherNotFoundException
     */
    public function getLessonsByUser(User $user): Collection
    {
        if (in_array(UserRoles::STUDENT, $user->getRoles(), true)) {
            return $this->getLessonsByStudent($user);
        }

        if (in_array(UserRoles::TEACHER, $user->getRoles(), true)) {
            return $this->getLessonsByTeacher($user);
        }

        return new ArrayCollection();
    }

    /**
     * @throws StudentNotFoundException
     */
    private function getLessonsByStudent(User $user): Collection
    {
        $student = $this->studentRepository->findOneBy(['associatedUser' => $user->getId()]);

        if (!$student) {
            throw new StudentNotFoundException();
        }

        return $student->getClassroom()->getLessons();
    }

    /**
     * @throws TeacherNotFoundException
     */
    private function getLessonsByTeacher(User $user): Collection
    {
        $teacher = $this->teacherRepository->findOneBy(['associatedUser' => $user->getId()]);

        if (!$teacher) {
            throw new TeacherNotFoundException();
        }

        return $teacher->getLesson();
    }

    public function userHasAccessToLesson(User $user, Lesson $lesson): bool
    {
        if (in_array(UserRoles::STUDENT, $user->getRoles(), true)) {
            $student = $this->studentRepository->findOneBy(['associatedUser' => $user->getId()]);

            return $student->getClassroom()->getLessons()->contains($lesson);
        } elseif (in_array(UserRoles::TEACHER, $user->getRoles(), true)) {
            $teacher = $this->teacherRepository->findOneBy(['associatedUser' => $user->getId()]);

            return $teacher->getLesson()->contains($lesson);
        }

        return false;
    }
}
