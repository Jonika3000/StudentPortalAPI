<?php

namespace App\Services;

use App\Entity\StudentSubmission;
use App\Entity\User;
use App\Params\FilesParams\StudentSubmissionFilesParams;
use App\Params\StudentSubmission\StudentSubmissionPostParams;
use App\Repository\StudentSubmissionRepository;
use App\Shared\Response\Exception\Homework\HomeworkPermissionException;
use App\Shared\Response\Exception\Student\StudentNotFoundException;

class StudentSubmissionService
{
    public function __construct(
        private StudentSubmissionRepository $submissionRepository,
        private readonly HomeworkService $homeworkService,
        private readonly StudentService $studentService,
        private readonly StudentSubmissionFileService $studentSubmissionFileService,
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
}
