<?php

namespace App\Encoder\Homework;

use App\Entity\Homework;
use App\Shared\Response\Common\DTO\User;
use App\Shared\Response\Homework\DTO\HomeworkFile;
use App\Shared\Response\Homework\DTO\Lesson;
use App\Shared\Response\Homework\DTO\Subject;
use App\Shared\Response\Homework\DTO\Teacher;
use App\Shared\Response\Homework\HomeworkInfoResponse;

class HomeworkInfoEncoder
{
    public function encode(Homework $homework): HomeworkInfoResponse
    {
        return new HomeworkInfoResponse(
            id: $homework->getId(),
            teacher: new Teacher(
                id: $homework->getTeacher()->getId(),
                associatedUser: new User(
                    id: $homework->getTeacher()->getAssociatedUser()->getId(),
                    uuid: $homework->getTeacher()->getAssociatedUser()->getUuid(),
                    firstName: $homework->getTeacher()->getAssociatedUser()->getFirstName(),
                    secondName: $homework->getTeacher()->getAssociatedUser()->getSecondName(),
                    email: $homework->getTeacher()->getAssociatedUser()->getEmail(),
                    avatarPath: $homework->getTeacher()->getAssociatedUser()->getAvatarPath(),
                ),
            ),
            description: $homework->getDescription(),
            lesson: new Lesson(
                id: $homework->getLesson()->getId(),
                subject: new Subject(
                    id: $homework->getLesson()->getSubject()->getId(),
                    name: $homework->getLesson()->getSubject()->getName(),
                    description: $homework->getLesson()->getSubject()->getDescription(),
                    imagePath: $homework->getLesson()->getSubject()->getImagePath(),
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
