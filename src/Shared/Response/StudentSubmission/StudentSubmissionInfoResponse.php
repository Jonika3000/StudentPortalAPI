<?php

namespace App\Shared\Response\StudentSubmission;

use App\Shared\Response\Common\DTO\Subject;
use App\Shared\Response\StudentSubmission\DTO\Homework;
use App\Shared\Response\StudentSubmission\DTO\Student;
use App\Shared\Response\StudentSubmission\DTO\StudentSubmissionFile;

class StudentSubmissionInfoResponse
{
    /**
     * @param StudentSubmissionFile[] $files
     */
    public function __construct(
        public int $id,
        public string $submittedDate,
        public string $comment,
        public $grade,
        public Student $student,
        public Homework $homework,
        public Subject $subject,
        public array $files,
    ) {
    }
}
