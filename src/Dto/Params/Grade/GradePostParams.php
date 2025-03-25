<?php

namespace App\Dto\Params\Grade;

class GradePostParams
{
    public function __construct(
        public int $grade,
        public string $comment,
        public int $studentSubmission,
    ) {
    }
}
