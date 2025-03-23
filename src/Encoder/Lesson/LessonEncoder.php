<?php

namespace App\Encoder\Lesson;

use App\Encoder\Common\SubjectEncoder;
use App\Entity\Lesson as LessonEntity;
use App\Shared\Response\Common\DTO\Lesson as LessonResponse;

class LessonEncoder
{
    public function encode(LessonEntity $lesson): LessonResponse
    {
        $subjectEncoder = new SubjectEncoder();
        $subjectEncoded = $subjectEncoder->encode($lesson->getSubject());

        return new LessonResponse(
            id: $lesson->getId(),
            subject: $subjectEncoded
        );
    }
}
