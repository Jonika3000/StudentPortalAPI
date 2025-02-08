<?php

namespace App\Request\Password;

use App\Shared\BaseRequest;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordResetRequestRequest extends BaseRequest
{
    #[NotBlank]
    #[Email]
    public ?string $email = null;
}
