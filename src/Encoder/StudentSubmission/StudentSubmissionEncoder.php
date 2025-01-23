<?php

namespace App\Encoder\StudentSubmission;

use App\Entity\StudentSubmission;
use App\Shared\Response\StudentSubmission\StudentSubmissionResponse;

class StudentSubmissionEncoder
{
    public function encode(StudentSubmission $studentSubmission): StudentSubmissionResponse
    {
        return new StudentSubmissionResponse(
            id: $studentSubmission->getId(),
            submittedDate: $studentSubmission->getSubmittedDate()?->format(\DateTimeInterface::ATOM),
            comment: $studentSubmission->getComment(),
            grade: $studentSubmission->getGrade(),
        );
    }
}
