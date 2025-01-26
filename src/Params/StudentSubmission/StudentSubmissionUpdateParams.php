<?php

namespace App\Params\StudentSubmission;

class StudentSubmissionUpdateParams
{
    public function __construct(
        public string $comment,
    ) {
    }
}
