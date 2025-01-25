<?php

namespace App\Services;

use App\Entity\StudentSubmission;
use App\Entity\User;
use App\Params\FilesParams\StudentSubmissionFilesParams;
use App\Params\StudentSubmission\StudentSubmissionPostParams;
use App\Repository\StudentSubmissionRepository;
use App\Shared\Response\Exception\AccessDeniedException;
use App\Shared\Response\Exception\Homework\HomeworkPermissionException;
use App\Shared\Response\Exception\Student\StudentNotFoundException;

readonly class StudentSubmissionService
{
    public function __construct(
        private StudentSubmissionRepository $submissionRepository,
        private HomeworkService $homeworkService,
        private StudentService $studentService,
        private StudentSubmissionFileService $studentSubmissionFileService,
    ) {
    }

    /**
     * @throws HomeworkPermissionException
     * @throws StudentNotFoundException
     */
    public function postAction(StudentSubmissionPostParams $params, User $user, ?StudentSubmissionFilesParams $files = null): StudentSubmission
    {
        $homework = $this->homeworkService->findHomeworkById($params->homework);
        if (!$homework || !$this->homeworkService->isHomeworkBelongsToStudent($homework, $user)) {
            throw new HomeworkPermissionException();
        }

        $student = $this->studentService->getStudentByUser($user);

        $studentSubmission = new StudentSubmission();
        $studentSubmission->setHomework($homework);
        $studentSubmission->setStudent($student);
        $studentSubmission->setComment($params->comment);
        $studentSubmission->setSubmittedDate(new \DateTime());

        if ($files) {
            foreach ($files->files as $file) {
                $this->studentSubmissionFileService->saveStudentSubmissionFile($file, $studentSubmission);
            }
        }

        $this->submissionRepository->saveAction($studentSubmission);

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
}
