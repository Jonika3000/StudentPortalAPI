<?php

namespace App\Request\StudentSubmission;

use App\Shared\BaseRequest;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

class StudentSubmissionPostRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[Length(
        max: 255,
        maxMessage: 'Comment cannot be longer than {{ limit }} characters.'
    )]
    public string $comment;

    #[NotBlank]
    #[NotNull]
    public int $homework;

    #[NotBlank]
    #[Groups('input')]
    #[Type(\DateTimeInterface::class)]
    public ?\DateTimeInterface $submittedDate;
}
