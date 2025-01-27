<?php

namespace App\Services;

use App\Entity\Homework;
use App\Entity\HomeworkFile;
use App\Repository\HomeworkFileRepository;
use App\Utils\FileHelper;
use Symfony\Component\Uid\Uuid;

readonly class HomeworkFileService
{
    public function __construct(
        private FileHelper $fileHelper,
        private HomeworkFileRepository $homeworkFileRepository,
    ) {
    }

    public function saveHomeworkFile($file, Homework $homework): void
    {
        $homeworkFile = new HomeworkFile();
        $homeworkFile->setHomework($homework);
        $filePath = $this->fileHelper->uploadFile($file, '/files/homework/', false);
        $homeworkFile->setPath($filePath);
        $homeworkFile->setName(Uuid::v1());

        $this->homeworkFileRepository->saveAction($homeworkFile);
    }

    public function removeHomeworkFile(HomeworkFile $homeworkFile): void
    {
        $this->homeworkFileRepository->deleteAction($homeworkFile);
    }
}
