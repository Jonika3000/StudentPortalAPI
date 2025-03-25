<?php

namespace App\Dto\Request\User;

use App\Enum\Gender;
use App\Shared\Request\BaseRequest;
use App\Validator\Constraint\OnlyLetters;
use App\Validator\Constraint\PhoneNumber;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class RegisterRequest extends BaseRequest
{
    #[NotBlank]
    #[Groups('input')]
    public string $password;

    #[NotBlank]
    #[OnlyLetters]
    #[Groups('input')]
    public string $firstName;

    #[NotBlank]
    #[OnlyLetters]
    #[Groups('input')]
    public string $secondName;

    #[NotBlank]
    #[Groups('input')]
    #[Type(\DateTimeInterface::class)]
    public ?\DateTimeInterface $birthday = null;

    #[NotBlank]
    #[Email]
    #[Groups('input')]
    public ?string $email = null;

    #[NotBlank]
    #[Groups('input')]
    public ?string $address = null;

    #[NotBlank]
    #[PhoneNumber]
    #[Groups('input')]
    public ?string $phoneNumber = null;

    #[NotBlank]
    #[Groups('input')]
    public ?Gender $gender = null;
}
