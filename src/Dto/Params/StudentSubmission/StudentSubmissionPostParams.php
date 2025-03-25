<?php

namespace App\Dto\Params\StudentSubmission;

class StudentSubmissionPostParams
{
    public function __construct(
        public string $comment,
        public int $homework,
    ) {
    }
}
