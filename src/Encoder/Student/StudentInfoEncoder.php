<?php

namespace App\Encoder\Student;

use App\Encoder\Common\UserEncoder;
use App\Entity\Student;
use App\Shared\Response\Student\DTO\Classroom;
use App\Shared\Response\Student\StudentInfoResponse;

class StudentInfoEncoder
{
    public function encode(Student $student): StudentInfoResponse
    {
        $user = $student->getAssociatedUser();
        $userEncoder = new UserEncoder();
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
