<?php

namespace App\Shared\Response\StudentSubmission;

class StudentSubmissionResponse
{
    public function __construct(
        public int $id,
        public string $submittedDate,
        public string $comment,
        public $grade,
    ) {
    }
}
