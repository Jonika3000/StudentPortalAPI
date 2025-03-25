<?php

namespace App\Dto\Params\FilesParams;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class HomeworkFilesParams
{
    public function __construct(
        public UploadedFile $file,
    ) {
    }
}
