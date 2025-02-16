<?php

namespace App\Decoder\FileBagDecoder;

use App\Params\FilesParams\UserEditFilesParams;
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
