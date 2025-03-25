<?php

namespace App\Serializer\Decoder\FileBagDecoder;

use App\Dto\Params\FilesParams\StudentSubmissionFilesParams;
use Symfony\Component\HttpFoundation\FileBag;

class StudentSubmissionFileBagDecoder
{
    public function decode(FileBag $fileBag): ?StudentSubmissionFilesParams
    {
        return $fileBag->get('files') ? new StudentSubmissionFilesParams(
            $fileBag->get('files')
        ) : null;
    }
}
