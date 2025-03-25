<?php

namespace App\Shared\Response\Classroom;

use App\Shared\Response\Classroom\DTO\Student;
use Symfony\Component\Uid\Uuid;

class ClassroomInfoResponse
{
    /**
     * @param Student[] $students
     */
    public function __construct(
        public readonly int $id,
        public readonly Uuid $uuid,
        public readonly array $students,
    ) {
    }
}
