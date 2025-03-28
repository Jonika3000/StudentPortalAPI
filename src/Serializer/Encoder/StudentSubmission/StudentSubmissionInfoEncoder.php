<?php

namespace App\Serializer\Encoder\StudentSubmission;

use App\Entity\StudentSubmission;
use App\Serializer\Encoder\Common\SubjectEncoder;
use App\Serializer\Encoder\Common\UserEncoder;
use App\Shared\Response\StudentSubmission\DTO\Homework;
use App\Shared\Response\StudentSubmission\DTO\Student;
use App\Shared\Response\StudentSubmission\DTO\StudentSubmissionFile;
use App\Shared\Response\StudentSubmission\StudentSubmissionInfoResponse;

class StudentSubmissionInfoEncoder
{
    public function encode(StudentSubmission $submission): StudentSubmissionInfoResponse
    {
        $userEncoder = new UserEncoder();
        $subjectEncoder = new SubjectEncoder();

        $student = $submission->getStudent();
        $homework = $submission->getHomework();
        $lesson = $homework->getLesson();

        $userEncoded = $userEncoder->encode($student->getAssociatedUser());
        $subjectEncoded = $subjectEncoder->encode($lesson->getSubject());

        return new StudentSubmissionInfoResponse(
            id: $submission->getId(),
            submittedDate: $submission->getSubmittedDate()->format('Y-m-d H:i:s'),
            comment: $submission->getComment(),
            grade: $submission->getGrade(),
            student: new Student(
                id: $student->getId(),
                user: $userEncoded
            ),
            homework: new Homework(
                id: $homework->getId(),
                description: $homework->getDescription()
            ),
            subject: $subjectEncoded,
            files: array_map(fn ($file) => new StudentSubmissionFile(
                id: $file->getId(),
                name: $file->getName(),
                path: $file->getPath()
            ), $submission->getStudentSubmissionFiles()->toArray())
        );
    }
}
