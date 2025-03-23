<?php

namespace App\EventListener\HomeworkFile;

use App\Entity\HomeworkFile;
use App\Helper\FileHelper;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: HomeworkFile::class)]
class HomeworkFileEventListener
{
    public function __construct(private readonly FileHelper $fileHelper)
    {
    }

    public function preRemove(HomeworkFile $homeworkFile, LifecycleEventArgs $args): void
    {
        $this->fileHelper->deleteFile($homeworkFile->getPath(), false);
    }
}
