<?php

namespace App\Shared\Response\Student;

use App\Shared\Response\Common\DTO\User;
use App\Shared\Response\Student\DTO\Classroom;

class StudentInfoResponse
{
    public function __construct(
        public readonly int $id,
        public readonly User $user,
        public readonly string $contactParent,
        public readonly Classroom $classroom,
    ) {
    }
}
