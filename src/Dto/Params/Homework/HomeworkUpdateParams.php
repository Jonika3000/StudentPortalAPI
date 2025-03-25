<?php

namespace App\Dto\Params\Homework;

class HomeworkUpdateParams
{
    public function __construct(
        public string $description,
        public \DateTimeInterface $deadline,
    ) {
    }
}
