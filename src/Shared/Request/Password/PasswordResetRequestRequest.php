<?php

namespace App\Shared\Request\Password;

use App\Shared\Request\BaseRequest;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordResetRequestRequest extends BaseRequest
{
    #[NotBlank]
    #[Email]
    public ?string $email = null;
}
