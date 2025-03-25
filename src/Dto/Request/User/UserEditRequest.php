<?php

namespace App\Dto\Request\User;

use App\Shared\Request\BaseRequest;
use App\Validator\Constraint\PhoneNumber;

class UserEditRequest extends BaseRequest
{
    public ?string $address = null;

    #[PhoneNumber]
    public ?string $phoneNumber = null;
}
