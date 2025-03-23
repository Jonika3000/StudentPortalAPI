<?php

namespace App\Encoder\Classroom;

use App\Encoder\Common\UserEncoder;
use App\Entity\Classroom;
use App\Shared\Response\Classroom\ClassroomInfoResponse;
use App\Shared\Response\Classroom\DTO\Student;

class ClassroomInfoEncoder
{
    public function encode(Classroom $classroom): ClassroomInfoResponse
    {
        $userEncoder = new UserEncoder();

        $students = array_map(
            fn ($student) => new Student(
                $student->getId(),
                $userEncoder->encode($student->getAssociatedUser()),
                $student->getContactParent()
            ),
            $classroom->getStudents()->toArray()
        );

        return new ClassroomInfoResponse(
            $classroom->getId(),
            $classroom->getUuid(),
            $students
        );
    }
}
