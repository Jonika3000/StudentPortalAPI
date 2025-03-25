<?php

namespace App\Shared\Response\Student\DTO;

use Symfony\Component\Uid\Uuid;

class Classroom
{
    public function __construct(
        public readonly int $id,
        public readonly Uuid $uuid,
    ) {
    }
}
