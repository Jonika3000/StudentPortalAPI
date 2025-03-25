<?php

namespace App\Serializer\Encoder\Homework;

use App\Entity\Homework;
use App\Serializer\Encoder\Common\UserEncoder;
use App\Serializer\Encoder\Lesson\LessonEncoder;
use App\Shared\Response\Homework\DTO\HomeworkFile;
use App\Shared\Response\Homework\DTO\Teacher;
use App\Shared\Response\Homework\HomeworkInfoResponse;

class HomeworkInfoEncoder
{
    public function encode(Homework $homework): HomeworkInfoResponse
    {
        $userEncoder = new UserEncoder();
        $lessonEncoder = new LessonEncoder();

        $user = $homework->getTeacher()->getAssociatedUser();
        $lesson = $homework->getLesson();

        $userEncoded = $userEncoder->encode($user);
        $lessonEncoded = $lessonEncoder->encode($lesson);

        return new HomeworkInfoResponse(
            id: $homework->getId(),
            teacher: new Teacher(
                id: $homework->getTeacher()->getId(),
                associatedUser: $userEncoded
            ),
            description: $homework->getDescription(),
            lesson: $lessonEncoded,
            deadline: $homework->getDeadline(),
            homeworkFiles: array_map(fn ($file) => new HomeworkFile(
                id: $file->getId(),
                name: $file->getName(),
                path: $file->getPath(),
            ), $homework->getHomeworkFiles()->toArray()),
        );
    }
}
