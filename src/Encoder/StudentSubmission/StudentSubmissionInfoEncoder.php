<?php

namespace App\Encoder\StudentSubmission;

use App\Encoder\Common\UserEncoder;
use App\Entity\StudentSubmission;
use App\Shared\Response\Common\DTO\Subject;
use App\Shared\Response\StudentSubmission\DTO\Homework;
use App\Shared\Response\StudentSubmission\DTO\Student;
use App\Shared\Response\StudentSubmission\DTO\StudentSubmissionFile;
use App\Shared\Response\StudentSubmission\StudentSubmissionInfoResponse;

class StudentSubmissionInfoEncoder
{
    public function encode(StudentSubmission $submission): StudentSubmissionInfoResponse
    {
        $userEncoder = new UserEncoder();

        $student = $submission->getStudent();
        $userEncoded = $userEncoder->encode($student->getAssociatedUser());
        $homework = $submission->getHomework();
        $lesson = $homework->getLesson();
        $subject = $lesson->getSubject();

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
            subject: new Subject(
                id: $subject->getId(),
                name: $subject->getName(),
                description: $subject->getDescription(),
                imagePath: $subject->getImagePath()
            ),
            files: array_map(fn ($file) => new StudentSubmissionFile(
                id: $file->getId(),
                name: $file->getName(),
                path: $file->getPath()
            ), $submission->getStudentSubmissionFiles()->toArray())
        );
    }
}
