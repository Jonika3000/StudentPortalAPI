<?php

namespace App\Serializer\Decoder\Password;

use App\Dto\Params\Password\PasswordResetParams;
use App\Dto\Request\Password\PasswordResetRequest;

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
