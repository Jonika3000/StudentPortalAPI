<?php

namespace App\Decoder\FileBagDecoder;

use App\Params\FilesParams\HomeworkFilesParams;
use Symfony\Component\HttpFoundation\FileBag;

class HomeworkFileBagDecoder
{
    public function decode(FileBag $fileBag): HomeworkFilesParams
    {
        return new HomeworkFilesParams(
            $fileBag->get('files')
        );
    }
}