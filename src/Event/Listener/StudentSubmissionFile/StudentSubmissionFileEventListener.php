<?php

namespace App\Event\Listener\StudentSubmissionFile;

use App\Entity\StudentSubmissionFile;
use App\Support\Helper\FileHelper;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class StudentSubmissionFileEventListener
{
    public function __construct(private readonly FileHelper $fileHelper)
    {
    }

    public function preRemove(StudentSubmissionFile $studentSubmissionFile, LifecycleEventArgs $args): void
    {
        $this->fileHelper->deleteFile($studentSubmissionFile->getPath(), false);
    }
}
