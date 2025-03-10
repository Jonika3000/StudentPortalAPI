<?php

namespace App\Shared\Response\Classroom\DTO;

use App\Shared\Response\DTO\Common\User;

class Student
{
    public function __construct(
        public readonly int $id,
        public readonly User $associatedUser,
        public readonly string $contactParent,
    ) {
    }
}
