<?php

namespace App\Encoder\Lesson;

use App\Entity\Lesson as LessonEntity;
use App\Shared\Response\Common\DTO\Lesson as LessonResponse;
use App\Shared\Response\Common\DTO\Subject;

class LessonEncoder
{
    public function encode(LessonEntity $lesson): LessonResponse
    {
        return new LessonResponse(
            id: $lesson->getId(),
            subject: new Subject(
                id: $lesson->getSubject()->getId(),
                name: $lesson->getSubject()->getName(),
                description: $lesson->getSubject()->getDescription(),
                imagePath: $lesson->getSubject()->getImagePath(),
            ),
        );
    }
}
