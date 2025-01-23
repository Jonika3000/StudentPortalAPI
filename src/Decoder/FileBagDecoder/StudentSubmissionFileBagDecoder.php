<?php

namespace App\Decoder\FileBagDecoder;

use App\Params\FilesParams\StudentSubmissionFilesParams;
use Symfony\Component\HttpFoundation\FileBag;

class StudentSubmissionFileBagDecoder
{
    public function decode(FileBag $fileBag): StudentSubmissionFilesParams
    {
        return new StudentSubmissionFilesParams(
            $fileBag->get('files')
        );
    }
}
