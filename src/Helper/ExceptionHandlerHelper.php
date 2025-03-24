<?php

namespace App\Helper;

use App\Mapper\ExceptionMapper;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionHandlerHelper
{
    public function __construct(
        private readonly LogHelper $logger
    ) {
    }

    public function handle(\Exception $exception): JsonResponse
    {
        $this->logger->logError($exception);

        return ExceptionMapper::map($exception);
    }
}
