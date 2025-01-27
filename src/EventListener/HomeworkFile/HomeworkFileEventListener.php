<?php

namespace App\EventListener\HomeworkFile;

use App\Entity\HomeworkFile;
use App\Utils\FileHelper;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: HomeworkFile::class)]
class HomeworkFileEventListener
{
    private FileHelper $fileHelper;

    public function __construct(FileHelper $fileHelper)
    {
        $this->fileHelper = $fileHelper;
    }

    public function preRemove(HomeworkFile $homeworkFile, LifecycleEventArgs $args): void
    {
        $this->fileHelper->deleteFile($homeworkFile->getPath(), false);
    }
}
