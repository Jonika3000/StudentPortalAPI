<?php

namespace App\Params\StudentSubmission;

class StudentSubmissionPostParams
{
    public function __construct(
        public string $comment,
        public int $homework,
        public \DateTimeInterface $submittedDate,
    ) {
    }
}
