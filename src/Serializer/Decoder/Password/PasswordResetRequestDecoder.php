<?php

namespace App\Serializer\Decoder\Password;

use App\Dto\Params\Password\PasswordResetRequestParams;
use App\Dto\Request\Password\PasswordResetRequestRequest;

class PasswordResetRequestDecoder
{
    public function decode(PasswordResetRequestRequest $request): PasswordResetRequestParams
    {
        return new PasswordResetRequestParams(
            $request->email
        );
    }
}
