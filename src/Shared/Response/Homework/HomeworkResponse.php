<?php

namespace App\Shared\Response\Homework;

use App\Shared\Response\StudentSubmission\StudentSubmissionResponse;

class HomeworkResponse
{
    public function __construct(
        public int $id,
        public string $description,
        public string $deadline,
        public array $lesson,
        public ?StudentSubmissionResponse $studentSubmission,
    ) {
    }
}
