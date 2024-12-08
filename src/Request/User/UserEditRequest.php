<?php

namespace App\Request\User;

use App\Shared\BaseRequest;
use App\Validator\Constraint\PhoneNumber;

class UserEditRequest extends BaseRequest
{
    public ?string $address;

    #[PhoneNumber]
    public ?string $phoneNumber = null;
}
