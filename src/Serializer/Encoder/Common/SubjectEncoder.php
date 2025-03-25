<?php

namespace App\Serializer\Encoder\Common;

use App\Entity\Subject;
use App\Shared\Response\Common\DTO\Subject as SubjectDTO;

class SubjectEncoder
{
    public function encode(Subject $subject): SubjectDTO
    {
        return new SubjectDTO(
            id: $subject->getId(),
            name: $subject->getName(),
            description: $subject->getDescription(),
            imagePath: $subject->getImagePath()
        );
    }
}
