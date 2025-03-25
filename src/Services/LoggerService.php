<?php

namespace App\Services;

use Psr\Log\LoggerInterface;

class LoggerService
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function logError(\Exception $exception): void
    {
        $this->logger->error('Exception occurred', [
            'exception' => $exception::class,
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }

    public function logEvent($message): void
    {
        $this->logger->info($message);
    }
}
