<?php

namespace App\EventListener\StudentSubmissionFile;

use App\Entity\StudentSubmissionFile;
use App\Utils\FileHelper;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class StudentSubmissionFileEventListener
{
    private FileHelper $fileHelper;

    public function __construct(FileHelper $fileHelper)
    {
        $this->fileHelper = $fileHelper;
    }

    public function preRemove(StudentSubmissionFile $studentSubmissionFile, LifecycleEventArgs $args): void
    {
        $this->fileHelper->deleteFile($studentSubmissionFile->getPath(), false);
    }
}