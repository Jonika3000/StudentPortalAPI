<?php

namespace App\Shared\Response\Homework;

use App\Shared\Response\Homework\DTO\HomeworkFile;
use App\Shared\Response\Homework\DTO\Lesson;
use App\Shared\Response\Homework\DTO\Teacher;

class HomeworkInfoResponse
{
    /**
     * @param HomeworkFile[] $homeworkFiles
     */
    public function __construct(
        public readonly int $id,
        public readonly Teacher $teacher,
        public readonly string $description,
        public readonly Lesson $lesson,
        public readonly \DateTimeInterface $deadline,
        public readonly array $homeworkFiles,
    ) {
    }
}
