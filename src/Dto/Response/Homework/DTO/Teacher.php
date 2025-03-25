<?php

namespace App\Shared\Response\Homework\DTO;

use App\Shared\Response\Common\DTO\User;

class Teacher
{
    public function __construct(
        public readonly int $id,
        public readonly User $associatedUser,
    ) {
    }
}
