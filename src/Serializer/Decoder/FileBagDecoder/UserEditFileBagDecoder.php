<?php

namespace App\Serializer\Decoder\FileBagDecoder;

use App\Dto\Params\FilesParams\UserEditFilesParams;
use Symfony\Component\HttpFoundation\FileBag;

class UserEditFileBagDecoder
{
    public function decode(FileBag $fileBag): ?UserEditFilesParams
    {
        return $fileBag->get('avatar') ? new UserEditFilesParams(
            $fileBag->get('avatar')
        ) : null;
    }
}
