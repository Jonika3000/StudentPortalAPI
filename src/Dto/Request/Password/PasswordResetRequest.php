<?php

namespace App\Dto\Request\Password;

use App\Shared\Request\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordResetRequest extends BaseRequest
{
    #[NotBlank]
    public ?string $resetToken = null;

    #[NotBlank]
    public ?string $newPassword = null;
}
