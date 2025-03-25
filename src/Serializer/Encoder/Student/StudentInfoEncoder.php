<?php

namespace App\Serializer\Encoder\Student;

use App\Entity\Student;
use App\Serializer\Encoder\Common\UserEncoder;
use App\Shared\Response\Student\DTO\Classroom;
use App\Shared\Response\Student\StudentInfoResponse;

class StudentInfoEncoder
{
    public function encode(Student $student): StudentInfoResponse
    {
        $userEncoder = new UserEncoder();

        $user = $student->getAssociatedUser();
        $classroom = $student->getClassroom();
        $userEncoded = $userEncoder->encode($user);

        return new StudentInfoResponse(
            id: $student->getId(),
            user: $userEncoded,
            contactParent: $student->getContactParent(),
            classroom: new Classroom(
                id: $classroom->getId(),
                uuid: $classroom->getUuid(),
            ),
        );
    }
}
