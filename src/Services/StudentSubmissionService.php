<?php

namespace App\Services;

use App\Dto\Params\FilesParams\StudentSubmissionFilesParams;
use App\Dto\Params\StudentSubmission\StudentSubmissionPostParams;
use App\Dto\Params\StudentSubmission\StudentSubmissionUpdateParams;
use App\Entity\StudentSubmission;
use App\Entity\User;
use App\Repository\StudentSubmissionRepository;
use App\Shared\Response\Exception\AccessDeniedException;
use App\Shared\Response\Exception\Homework\HomeworkPermissionException;
use App\Shared\Response\Exception\Student\StudentNotFoundException;
use App\Shared\Response\Exception\Teacher\TeacherNotFoundException;

readonly class StudentSubmissionService
{
    public function __construct(
        private StudentSubmissionRepository $submissionRepository,
        private HomeworkService $homeworkService,
        private StudentService $studentService,
        private StudentSubmissionFileService $studentSubmissionFileService,
        private TeacherService $teacherService,
    ) {
    }

    /**
     * @throws HomeworkPermissionException
     * @throws StudentNotFoundException
     */
    public function postAction(StudentSubmissionPostParams $params, User $user, ?StudentSubmissionFilesParams $files = null): StudentSubmission
    {
        $homework = $this->homeworkService->findHomeworkById($params->homework);
        $student = $this->studentService->getStudentByUser($user);
        if (!$homework || !$this->homeworkService->isHomeworkBelongsToStudent($homework, $student)) {
            throw new HomeworkPermissionException();
        }

        $studentSubmission = new StudentSubmission();
        $studentSubmission->setHomework($homework);
        $studentSubmission->setStudent($student);
        $studentSubmission->setComment($params->comment);
        $studentSubmission->setSubmittedDate(new \DateTime());

        $this->submissionRepository->saveAction($studentSubmission);

        if ($files) {
            $this->studentSubmissionFileService->saveStudentSubmissionFile($files->file, $studentSubmission);
        }

        return $studentSubmission;
    }

    /**
     * @throws StudentNotFoundException
     * @throws AccessDeniedException
     */
    public function deleteAction(StudentSubmission $studentSubmission, User $user): void
    {
        $student = $this->studentService->getStudentByUser($user);
        if ($studentSubmission->getStudent()->getId() !== $student->getId()) {
            throw new AccessDeniedException();
        }

        $this->submissionRepository->deleteAction($studentSubmission);

        foreach ($studentSubmission->getStudentSubmissionFiles() as $studentSubmissionFile) {
            $this->studentSubmissionFileService->removeStudentSubmissionFile($studentSubmissionFile);
        }
    }

    /**
     * @throws AccessDeniedException
     */
    public function getByUser(StudentSubmission $studentSubmission, User $user): StudentSubmission
    {
        try {
            $student = $this->studentService->getStudentByUser($user);
            if ($studentSubmission->getStudent()->getId() !== $student->getId()) {
                throw new AccessDeniedException();
            }
        } catch (StudentNotFoundException) {
            try {
                $teacher = $this->teacherService->getTeacherByAssociatedUser($user);

                $homeworkLesson = $studentSubmission->getHomework()->getLesson();
                if (!$teacher->getLesson()->contains($homeworkLesson)) {
                    throw new AccessDeniedException();
                }
            } catch (TeacherNotFoundException) {
                throw new AccessDeniedException();
            }
        }

        return $studentSubmission;
    }

    /**
     * @throws AccessDeniedException
     * @throws StudentNotFoundException
     */
    public function updateAction(StudentSubmission $studentSubmission, StudentSubmissionUpdateParams $params, User $user, ?StudentSubmissionFilesParams $files = null): StudentSubmission
    {
        $student = $this->studentService->getStudentByUser($user);

        if ($studentSubmission->getStudent()->getId() != $student->getId()) {
            throw new AccessDeniedException();
        }
        $studentSubmission->setComment($params->comment);

        $this->submissionRepository->saveAction($studentSubmission);

        if ($files) {
            foreach ($studentSubmission->getStudentSubmissionFiles() as $studentSubmissionFile) {
                $this->studentSubmissionFileService->removeStudentSubmissionFile($studentSubmissionFile);
            }

            $this->studentSubmissionFileService->saveStudentSubmissionFile($files->file, $studentSubmission);
        }

        return $studentSubmission;
    }
}
