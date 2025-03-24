<?php

namespace App\Decoder\Password;

use App\Params\Password\PasswordResetRequestParams;
use App\Shared\Request\Password\PasswordResetRequestRequest;

class PasswordResetRequestDecoder
{
    public function decode(PasswordResetRequestRequest $request): PasswordResetRequestParams
    {
        return new PasswordResetRequestParams(
            $request->email
        );
    }
}
