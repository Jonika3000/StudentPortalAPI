<?php

namespace App\Shared\Response\StudentSubmission\DTO;

class Homework
{
    public function __construct(
        public readonly int $id,
        public readonly string $description
    ) {
    }
}