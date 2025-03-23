<?php

namespace App\Params\FilesParams;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class HomeworkFilesParams
{
    public function __construct(
        public UploadedFile $file,
    ) {
    }
}
