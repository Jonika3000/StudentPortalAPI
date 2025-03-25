<?php

namespace App\Dto\Params\Password;

class PasswordResetRequestParams
{
    public function __construct(
        public string $email,
    ) {
    }
}
