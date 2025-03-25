<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Services\MailerService;
use App\Services\UserService;
use App\Support\Helper\LogHelper;
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
        private readonly UserService $userService,
        private readonly LogHelper $logger,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $today = new \DateTime();
        $users = $this->userRepository->findUsersByBirthday($today);

        if (empty($users)) {
            $output->writeln('No birthdays today.');

            return Command::SUCCESS;
        }

        foreach ($users as $user) {
            try {
                $this->userService->congratulateOnBirthday($user);
                $output->writeln('Birthday email sent to: '.$user->getEmail());
            } catch (TransportExceptionInterface $e) {
                $this->logger->logError($e);
                $output->writeln('Error occurred: '.$e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
