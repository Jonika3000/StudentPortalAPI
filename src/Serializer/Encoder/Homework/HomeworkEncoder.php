<?php

namespace App\Serializer\Encoder\Homework;

use App\Entity\Homework;
use App\Entity\StudentSubmission;
use App\Serializer\Encoder\StudentSubmission\StudentSubmissionEncoder;
use App\Shared\Response\Homework\HomeworkResponse;

class HomeworkEncoder
{
    public function encode(Homework $homework, ?StudentSubmission $studentSubmission = null): HomeworkResponse
    {
        $studentSubmissionEncoder = new StudentSubmissionEncoder();

        $studentSubmissionResponse = $studentSubmission
            ? $studentSubmissionEncoder->encode($studentSubmission)
            : null;

        return new HomeworkResponse(
            id: $homework->getId(),
            description: $homework->getDescription(),
            deadline: $homework->getDeadline()?->format(\DateTimeInterface::ATOM),
            lesson: [
                'id' => $homework->getLesson()->getId(),
                'subject' => $homework->getLesson()->getSubject(),
            ],
            studentSubmission: $studentSubmissionResponse
        );
    }
}
