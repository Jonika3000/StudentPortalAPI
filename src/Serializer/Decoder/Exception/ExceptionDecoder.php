<?php

namespace App\Serializer\Decoder\Exception;

use App\Constants\ErrorCodes;
use App\Shared\Response\Exception\Homework\HomeworkPermissionException;
use App\Shared\Response\Exception\Student\StudentNotFoundException;
use App\Shared\Response\Exception\Student\StudentSubmissionNotFound;
use App\Shared\Response\Exception\Teacher\TeacherNotFoundException;
use App\Shared\Response\Exception\User\AccessDeniedException;
use App\Shared\Response\Exception\User\IncorrectUserConfigurationException;
use App\Shared\Response\ResponseError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ExceptionDecoder
{
    public static array $errorMapping = [
        StudentSubmissionNotFound::class => [
            'code' => ErrorCodes::STUDENT_SUBMISSION_NOT_FOUND,
            'message' => 'Student submission not found.',
        ],
        TeacherNotFoundException::class => [
            'code' => ErrorCodes::TEACHER_NOT_FOUND,
            'message' => 'Teacher not found.',
        ],
        AccessDeniedException::class => [
            'code' => ErrorCodes::ACCESS_DENIED,
            'message' => 'Access denied: Teacher is not associated with this lesson.',
        ],
        IncorrectUserConfigurationException::class => [
            'code' => ErrorCodes::INCORRECT_USER_CONFIGURATION,
            'message' => 'User configuration incorrect.',
        ],
        StudentNotFoundException::class => [
            'code' => ErrorCodes::STUDENT_NOT_FOUND,
            'message' => 'Student not found.',
        ],
        TransportExceptionInterface::class => [
            'code' => ErrorCodes::MAIL_ERROR,
            'message' => 'Mail error occurred.',
        ],
        HomeworkPermissionException::class => [
            'code' => ErrorCodes::MAIL_ERROR,
            'message' => 'Homework doesn\'t belong to you',
        ],
        \App\Shared\Response\Exception\AccessDeniedException::class => [
            'code' => ErrorCodes::HOME_WORK_PERMISSION,
            'message' => 'Access denied',
        ],
    ];

    public static function map(\Throwable $exception): JsonResponse
    {
        $error = ExceptionDecoder::$errorMapping[$exception::class] ?? null;

        if ('dev' === $_ENV['APP_ENV']) {
            return new JsonResponse([
                'error' => empty($exception->getMessage()) ? ($error['message'] ?? '') : $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($error) {
            $response = (new ResponseError())->setCode($error['code'])->setMessage($error['message']);

            return new JsonResponse($response->serializeToJsonString(), Response::HTTP_BAD_REQUEST);
        }

        $response = (new ResponseError())->setCode(ErrorCodes::UNEXPECTED_ERROR)->setMessage('An unexpected error occurred.');

        return new JsonResponse(json_decode($response->serializeToJsonString(), true), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
