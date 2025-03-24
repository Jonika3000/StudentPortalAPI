<?php

namespace App\Decoder\User;

use App\Params\User\RegisterParams;
use App\Shared\Request\User\RegisterRequest;

class RegisterRequestDecoder
{
    public function decode(RegisterRequest $request): RegisterParams
    {
        return new RegisterParams(
            $request->email,
            $request->firstName,
            $request->password,
            $request->secondName,
            $request->address,
            $request->phoneNumber,
            $request->gender,
            $request->birthday,
        );
    }
}
