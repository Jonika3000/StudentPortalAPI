<?php

namespace App\Encoder\Lesson;

use App\Entity\Lesson as LessonEntity;
use App\Shared\Response\Common\DTO\Lesson as LessonResponse;
use App\Shared\Response\Common\DTO\Subject;

class LessonEncoder
{
    public function encode(LessonEntity $lesson): LessonResponse
    {
        $subject = $lesson->getSubject();

        return new LessonResponse(
            id: $lesson->getId(),
            subject: new Subject(
                id: $subject->getId(),
                name: $subject->getName(),
                description: $subject->getDescription(),
                imagePath: $subject->getImagePath(),
            ),
        );
    }
}
