<?php

namespace App\Support\Helper;

use App\Serializer\Encoder\Exception\ExceptionEncoder;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionHandlerHelper
{
    public function __construct(
        private readonly LogHelper $logger,
    ) {
    }

    public function handle(\Exception $exception): JsonResponse
    {
        $this->logger->logError($exception);

        return ExceptionEncoder::map($exception);
    }
}
