<?php

namespace App\Shared\Response\Homework\DTO;

class HomeworkFile
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $path,
    ) {
    }
}
