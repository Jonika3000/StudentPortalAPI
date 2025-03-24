<?php

namespace App\Event;

use App\Entity\Grade;
use Symfony\Contracts\EventDispatcher\Event;

class GradeAssignedEvent extends Event
{
    public function __construct(
        private readonly Grade $grade,
    ) {
    }

    public function getGrade(): Grade
    {
        return $this->grade;
    }
}
