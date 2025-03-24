<?php

namespace App\Helper;

use Psr\Log\LoggerInterface;

class LoggingHelper
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function logError(\Exception $exception): void
    {
        $this->logger->error('Exception occurred', [
            'exception' => get_class($exception),
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
