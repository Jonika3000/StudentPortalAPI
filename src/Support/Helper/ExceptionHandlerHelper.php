<?php

namespace App\Support\Helper;

use App\Serializer\Decoder\Exception\ExceptionDecoder;
use App\Services\LoggerService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionHandlerHelper
{
    public function __construct(
        private readonly LoggerService $logger,
    ) {
    }

    public function handle(\Exception $exception): JsonResponse
    {
        $this->logger->logError($exception);

        return ExceptionDecoder::map($exception);
    }
}
