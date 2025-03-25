<?php

namespace App\Shared\Response\StudentSubmission\DTO;

use App\Shared\Response\Common\DTO\User;

class Student
{
    public function __construct(
        public readonly int $id,
        public readonly User $user,
    ) {
    }
}
