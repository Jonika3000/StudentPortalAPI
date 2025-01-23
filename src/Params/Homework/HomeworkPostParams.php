<?php

namespace App\Params\Homework;

class HomeworkPostParams
{
    public function __construct(
        public string $description,
        public int $lesson,
        public \DateTimeInterface $deadline,
    ) {
    }
}