<?php

namespace App\Params\FilesParams;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class StudentSubmissionFilesParams
{
    public function __construct(
        public UploadedFile $file,
    ) {
    }
}
