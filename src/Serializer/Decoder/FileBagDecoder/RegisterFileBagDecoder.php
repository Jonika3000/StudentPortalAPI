<?php

namespace App\Serializer\Decoder\FileBagDecoder;

use App\Dto\Params\FilesParams\RegisterFilesParams;
use Symfony\Component\HttpFoundation\FileBag;

class RegisterFileBagDecoder
{
    public function decode(FileBag $fileBag): ?RegisterFilesParams
    {
        return $fileBag->get('avatar') ? new RegisterFilesParams(
            $fileBag->get('avatar')
        ) : null;
    }
}
