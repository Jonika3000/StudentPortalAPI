<?php

namespace App\Encoder\Classroom;

use App\Entity\Classroom;
use App\Shared\Response\Classroom\ClassroomInfoResponse;
use App\Shared\Response\Classroom\DTO\Student;
use App\Shared\Response\Common\DTO\User;

class ClassroomInfoEncoder
{
    public function encode(Classroom $classroom): ClassroomInfoResponse
    {
        $students = array_map(
            fn ($student) => new Student(
                $student->getId(),
                new User(
                    $student->getAssociatedUser()->getId(),
                    $student->getAssociatedUser()->getUuid(),
                    $student->getAssociatedUser()->getFirstName(),
                    $student->getAssociatedUser()->getSecondName(),
                    $student->getAssociatedUser()->getEmail(),
                    $student->getAssociatedUser()->getAvatarPath()
                ),
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
