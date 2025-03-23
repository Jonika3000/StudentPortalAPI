<?php

namespace App\Encoder\Student;

use App\Entity\Student;
use App\Shared\Response\Common\DTO\User;
use App\Shared\Response\Student\DTO\Classroom;
use App\Shared\Response\Student\StudentInfoResponse;

class StudentInfoEncoder
{
    public function encode(Student $student): StudentInfoResponse
    {
        $user = $student->getAssociatedUser();
        $classroom = $student->getClassroom();

        return new StudentInfoResponse(
            id: $student->getId(),
            user: new User(
                id: $user->getId(),
                uuid: $user->getUuid(),
                firstName: $user->getFirstName(),
                secondName: $user->getSecondName(),
                email: $user->getEmail(),
                avatarPath: $user->getAvatarPath(),
            ),
            contactParent: $student->getContactParent(),
            classroom: new Classroom(
                id: $classroom->getId(),
                uuid: $classroom->getUuid(),
            ),
        );
    }
}
