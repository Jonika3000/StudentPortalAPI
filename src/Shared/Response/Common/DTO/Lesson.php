<?php

namespace App\Shared\Response\Common\DTO;

class Lesson
{
    public function __construct(
        public readonly int $id,
        public readonly Subject $subject,
    ) {
    }
}
