<?php

namespace App\Services;

use App\Encoder\Homework\HomeworkEncoder;
use App\Entity\Homework;
use App\Entity\User;
use App\Params\FilesParams\HomeworkFilesParams;
use App\Params\Homework\HomeworkPostParams;
use App\Params\Homework\HomeworkUpdateParams;
use App\Repository\HomeworkRepository;
use App\Repository\LessonRepository;
use App\Repository\StudentRepository;
use App\Shared\Response\Exception\Lesson\LessonNotFound;
use App\Shared\Response\Exception\Student\StudentNotFoundException;
use App\Shared\Response\Exception\Teacher\TeacherNotFoundException;
use App\Shared\Response\Exception\User\AccessDeniedException;

readonly class HomeworkService
{
    public function __construct(
        private TeacherService $teacherService,
        private LessonRepository $lessonRepository,
        private HomeworkRepository $homeworkRepository,
        private StudentRepository $studentRepository,
        private HomeworkEncoder $homeworkEncoder,
        private HomeworkFileService $homeworkFileService,
    ) {
    }

    /**
     * @throws TeacherNotFoundException
     * @throws LessonNotFound
     * @throws AccessDeniedException
     */
    public function postAction(HomeworkPostParams $params, User $user, ?HomeworkFilesParams $files = null): array
    {
        $teacher = $this->teacherService->getTeacherByAssociatedUser($user);

        $lesson = $this->lessonRepository->find($params->lesson);
        if (!$lesson) {
            throw new LessonNotFound();
        }
        $isTeacherAssignedToLesson = $teacher->getLesson()->contains($lesson);

        if (!$isTeacherAssignedToLesson) {
            throw new AccessDeniedException();
        }

        $homework = new Homework();
        $homework->setTeacher($teacher);
        $homework->setLesson($lesson);
        $homework->setDescription($params->description);
        $homework->setDeadline($params->deadline);

        $this->homeworkRepository->saveAction($homework);

        if ($files) {
            // foreach ($files->files as $file) {
            $this->homeworkFileService->saveHomeworkFile($files->files, $homework);
            // }
        }

        return ['message' => 'Success.'];
    }

    /**
     * @throws TeacherNotFoundException
     * @throws AccessDeniedException
     */
    public function checkAccessHomeworkTeacher(Homework $homework, User $user): void
    {
        $teacher = $this->teacherService->getTeacherByAssociatedUser($user);

        if (!$homework->getLesson()->getTeachers()->contains($teacher)) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @throws AccessDeniedException
     * @throws TeacherNotFoundException
     */
    public function checkAccessHomeworkTeacherChange(Homework $homework, User $user): void
    {
        $teacher = $this->teacherService->getTeacherByAssociatedUser($user);

        if ($teacher->getId() != $homework->getTeacher()->getId()) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @throws AccessDeniedException
     * @throws StudentNotFoundException
     */
    public function getHomeworkStudent(Homework $homework, User $user): array
    {
        $student = $this->studentRepository->findOneBy(['associatedUser' => $user->getId()]);
        if (!$student) {
            throw new StudentNotFoundException();
        }

        if ($homework->getLesson()->getClassroom() !== $student->getClassroom()) {
            throw new AccessDeniedException();
        }

        $studentSubmission = $homework->getStudentSubmissions()
            ->filter(fn ($submission) => $submission->getStudent() === $student)
            ->first();

        $response = $this->homeworkEncoder->encode($homework, $studentSubmission);

        return $response->toArray();
    }

    /**
     * @throws AccessDeniedException
     * @throws TeacherNotFoundException
     */
    public function deleteAction(Homework $homework, User $user): void
    {
        $this->checkAccessHomeworkTeacherChange($homework, $user);

        foreach ($homework->getHomeworkFiles() as $homeworkFile) {
            $this->homeworkFileService->removeHomeworkFile($homeworkFile);
        }

        $this->homeworkRepository->deleteAction($homework);
    }

    /**
     * @throws AccessDeniedException
     * @throws TeacherNotFoundException
     */
    public function updateAction(Homework $homework, User $user, HomeworkUpdateParams $params, ?HomeworkFilesParams $files = null): Homework
    {
        $this->checkAccessHomeworkTeacherChange($homework, $user);

        if ($files) {
            foreach ($homework->getHomeworkFiles() as $homeworkFile) {
                $this->homeworkFileService->removeHomeworkFile($homeworkFile);
            }

            foreach ($files->files as $file) {
                $this->homeworkFileService->saveHomeworkFile($file, $homework);
            }
        }

        $homework->setDeadline($params->deadline);
        $homework->setDescription($params->description);

        $this->homeworkRepository->saveAction($homework);

        return $homework;
    }

    public function isHomeworkBelongsToStudent(Homework $homework, User $student): bool
    {
        return $homework->getLesson()->getClassroom()->getStudents()->contains($student);
    }

    public function findHomeworkById(int $id): ?Homework
    {
        return $this->homeworkRepository->find($id);
    }
}
