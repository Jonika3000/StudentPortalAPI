<?php

namespace App\Serializer\Decoder\User;

use App\Dto\Params\User\UserEditParams;
use App\Dto\Request\User\UserEditRequest;

class UserEditRequestDecoder
{
    public function decode(UserEditRequest $request): UserEditParams
    {
        return new UserEditParams(
            $request->address,
            $request->phoneNumber,
        );
    }
}
