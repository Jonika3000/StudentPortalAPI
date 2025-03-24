<?php

namespace App\Decoder\Password;

use App\Params\Password\PasswordResetParams;
use App\Shared\Request\Password\PasswordResetRequest;

class PasswordResetDecoder
{
    public function decode(PasswordResetRequest $request): PasswordResetParams
    {
        return new PasswordResetParams(
            $request->resetToken,
            $request->newPassword
        );
    }
}
