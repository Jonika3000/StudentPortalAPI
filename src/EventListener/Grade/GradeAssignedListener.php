<?php

namespace App\EventListener\Grade;

use App\Event\GradeAssignedEvent;
use App\Helper\LogHelper;
use App\Services\MailerService;

class GradeAssignedListener
{
    public function __construct(
        private MailerService $mailerService,
        private LogHelper $logger,
    ) {
    }

    public function onGradeAssigned(GradeAssignedEvent $event)
    {
        $grade = $event->getGrade();
        $student = $grade->getStudentSubmission()->getStudent();
        $email = $student->getAssociatedUser()->getEmail();

        try {
            $this->mailerService->sendMail(
                $email,
                'Your work is appreciated!',
                'email/grade/grade_assigned.html.twig',
                ['grade' => $grade]
            );
            $this->logger->logEvent('Notification of assessment sent to student: '.$email);
        } catch (\Exception $e) {
            $this->logger->logError($e);
        }
    }
}
