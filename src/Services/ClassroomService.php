<?php

namespace App\Services;

use App\Repository\StudentRepository;

readonly class ClassroomService
{
    public function __construct(
        private StudentRepository $studentRepository,
    ) {
    }
}
