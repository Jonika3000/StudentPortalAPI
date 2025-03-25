<?php

namespace App\Services;

use App\Entity\StudentSubmission;
use App\Entity\StudentSubmissionFile;
use App\Repository\StudentSubmissionFileRepository;
use App\Support\Helper\FileHelper;
use Symfony\Component\Uid\Uuid;

readonly class StudentSubmissionFileService
{
    public function __construct(
        private FileHelper $fileHelper,
        private StudentSubmissionFileRepository $studentSubmissionFileRepository,
    ) {
    }

    public function saveStudentSubmissionFile($file, StudentSubmission $studentSubmission): void
    {
        $studentSubmissionFile = new StudentSubmissionFile();
        $studentSubmissionFile->setStudentSubmission($studentSubmission);
        $filePath = $this->fileHelper->uploadFile($file, '/files/student-submission/', false);
        $studentSubmissionFile->setPath($filePath);
        $studentSubmissionFile->setName(Uuid::v1());

        $this->studentSubmissionFileRepository->saveAction($studentSubmissionFile);
    }

    public function removeStudentSubmissionFile(StudentSubmissionFile $studentSubmissionFile): void
    {
        $this->fileHelper->deleteFile($studentSubmissionFile->getPath(), false);
        $this->studentSubmissionFileRepository->deleteAction($studentSubmissionFile);
    }
}
