<?php

namespace App\Shared\Response\Homework\DTO;

class Subject
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly string $imagePath,
    ) {
    }
}