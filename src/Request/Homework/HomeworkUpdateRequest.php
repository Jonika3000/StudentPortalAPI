<?php

namespace App\Request\Homework;

use App\Shared\BaseRequest;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

class HomeworkUpdateRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[Length(
        max: 255,
        maxMessage: 'Description cannot be longer than {{ limit }} characters.'
    )]
    public string $description;

    #[NotBlank]
    #[Groups('input')]
    #[Type(\DateTimeInterface::class)]
    public ?\DateTimeInterface $deadline = null;
}
