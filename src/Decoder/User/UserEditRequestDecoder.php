<?php

namespace App\Decoder\User;

use App\Params\User\UserEditParams;
use App\Shared\Request\User\UserEditRequest;

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
