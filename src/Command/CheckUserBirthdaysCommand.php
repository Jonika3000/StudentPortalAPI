<?php

namespace App\Command;

use App\Helper\LoggingHelper;
use App\Repository\UserRepository;
use App\Services\MailerService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:check-user-birthdays',
    description: 'Checks if any users have a birthday today and sends greetings.',
)]
class CheckUserBirthdaysCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly MailerService $mailerService,
        private readonly LoggingHelper $logger,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $today = new \DateTime();
        $usersWithBirthday = $this->userRepository->findUsersByBirthday($today);

        if (empty($usersWithBirthday)) {
            $output->writeln('No birthdays today.');

            return Command::SUCCESS;
        }

        foreach ($usersWithBirthday as $user) {
            try {
                $this->mailerService->sendMail(
                    $user->getEmail(),
                    'Happy Birthday!',
                    'email/user/birthday_email.html.twig'
                );
                $output->writeln('Birthday email sent to: '.$user->getEmail());
            } catch (TransportExceptionInterface $e) {
                $this->logger->logError($e);
                $output->writeln('Error occurred: '.$e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
