<?php

namespace App\Encoder\Homework;

use App\Encoder\Common\UserEncoder;
use App\Entity\Homework;
use App\Shared\Response\Common\DTO\Lesson;
use App\Shared\Response\Common\DTO\Subject;
use App\Shared\Response\Homework\DTO\HomeworkFile;
use App\Shared\Response\Homework\DTO\Teacher;
use App\Shared\Response\Homework\HomeworkInfoResponse;

class HomeworkInfoEncoder
{
    public function encode(Homework $homework): HomeworkInfoResponse
    {
        $user = $homework->getTeacher()->getAssociatedUser();
        $lesson = $homework->getLesson();
        $subject = $lesson->getSubject();
        $userEncoder = new UserEncoder();
        $userEncoded = $userEncoder->encode($user);

        return new HomeworkInfoResponse(
            id: $homework->getId(),
            teacher: new Teacher(
                id: $homework->getTeacher()->getId(),
                associatedUser: $userEncoded
            ),
            description: $homework->getDescription(),
            lesson: new Lesson(
                id: $lesson->getId(),
                subject: new Subject(
                    id: $subject->getId(),
                    name: $subject->getName(),
                    description: $subject->getDescription(),
                    imagePath: $subject->getImagePath(),
                ),
            ),
            deadline: $homework->getDeadline(),
            homeworkFiles: array_map(fn ($file) => new HomeworkFile(
                id: $file->getId(),
                name: $file->getName(),
                path: $file->getPath(),
            ), $homework->getHomeworkFiles()->toArray()),
        );
    }
}
