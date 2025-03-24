<?php

namespace App\Event;

use App\Entity\Grade;
use Symfony\Contracts\EventDispatcher\Event;

class GradeAssignedEvent extends Event
{
    private Grade $grade;

    public function __construct(Grade $grade)
    {
        $this->grade = $grade;
    }

    public function getGrade(): Grade
    {
        return $this->grade;
    }
}