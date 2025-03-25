<?php

namespace App\Event\Listener\Auth;

use App\Services\LoggerService;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class LoginFailureListener
{
    public function __construct(
        private readonly LoggerService $logger,
    ) {
    }

    public function onLoginFailure(LoginFailureEvent $event): void
    {
        $passport = $event->getPassport();
        $userIdentifier = $passport?->getUser()?->getUserIdentifier() ?? 'unknown';

        $this->logger->logEvent("Failed login attempt for user: {$userIdentifier}");
    }
}
