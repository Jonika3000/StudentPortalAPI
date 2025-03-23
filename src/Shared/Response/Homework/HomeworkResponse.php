<?php

namespace App\Shared\Response\Homework;

use App\Shared\Response\StudentSubmission\StudentSubmissionResponse;

class HomeworkResponse
{
    public function __construct(
        public int $id,
        public string $description,
        public string $deadline,
        public array $lesson,
        public ?StudentSubmissionResponse $studentSubmission,
    ) {
        //        $this->id = $homework->getId();
        //        $this->description = $homework->getDescription();
        //        $this->deadline = $homework->getDeadline()?->format(\DateTimeInterface::ATOM);
        //        $this->lesson = [
        //            'id' => $homework->getLesson()->getId(),
        //            'subject' => $homework->getLesson()->getSubject()->getName(),
        //        ];
        //        $this->studentSubmission = $studentSubmission
        //            ? new StudentSubmissionResponse($studentSubmission)
        //            : null;
    }

    // @TODO: delete this part
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'lesson' => $this->lesson,
            'studentSubmission' => $this->studentSubmission?->toArray(),
        ];
    }
}
