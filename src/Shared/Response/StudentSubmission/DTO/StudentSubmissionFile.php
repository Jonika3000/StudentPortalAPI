<?php

namespace App\Shared\Response\StudentSubmission\DTO;

class StudentSubmissionFile
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $path,
    ) {
    }
}
