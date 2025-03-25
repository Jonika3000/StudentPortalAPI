<?php

namespace App\Dto\Params\StudentSubmission;

class StudentSubmissionUpdateParams
{
    public function __construct(
        public string $comment,
    ) {
    }
}
