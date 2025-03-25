<?php

namespace App\Dto\Params\FilesParams;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserEditFilesParams
{
    public function __construct(
        public UploadedFile $avatar,
    ) {
    }
}
