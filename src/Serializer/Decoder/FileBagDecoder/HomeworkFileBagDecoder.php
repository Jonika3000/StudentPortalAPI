<?php

namespace App\Serializer\Decoder\FileBagDecoder;

use App\Dto\Params\FilesParams\HomeworkFilesParams;
use Symfony\Component\HttpFoundation\FileBag;

class HomeworkFileBagDecoder
{
    public function decode(FileBag $fileBag): ?HomeworkFilesParams
    {
        return $fileBag->get('files') ? new HomeworkFilesParams(
            $fileBag->get('files')
        ) : null;
    }
}
