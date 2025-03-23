<?php

namespace App\Services;

use App\Entity\User;
use App\Params\FilesParams\RegisterFilesParams;
use App\Params\FilesParams\UserEditFilesParams;
use App\Params\User\RegisterParams;
use App\Params\User\UserEditParams;
use App\Repository\UserRepository;
use App\Shared\Response\Exception\MailException;
use App\Shared\Response\Exception\User\IncorrectUserConfigurationException;
use App\Utils\FileHelper;
use Random\RandomException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Bundle\SecurityBundle\Security;

readonly class UserService
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private ValidatorService $validatorService,
        private UserRepository $userRepository,
        private FileHelper $fileHelper,
        private MailerService $mailerService,
        private ParameterBagInterface $params,
        private TokenStorageInterface $tokenStorage,
        private Security $security
    ) {
    }

    public function postAction(
        RegisterParams $params, ?RegisterFilesParams $files = null): User
    {
        $user = new User();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $params->password
        );
        $avatarPath = $this->fileHelper->uploadFile($files->avatar, '/images/avatars/', true);
        $user->setAvatarPath($avatarPath);
        $user->setPassword($hashedPassword);
        $user->setBirthday($params->birthday);
        $user->setEmail($params->email);
        $user->setFirstName($params->firstName);
        $user->setGender($params->gender);
        $user->setPhoneNumber($params->phoneNumber);
        $user->setAddress($params->address);
        $user->setSecondName($params->secondName);

        $this->validatorService->validateObject($user);

        $this->userRepository->saveAction($user);

        return $user;
    }

    public function getUserByToken(TokenInterface $token): \Symfony\Component\Security\Core\User\UserInterface
    {
        return $token->getUser();
    }

    /**
     * @throws RandomException
     * @throws MailException
     */
    public function resetPasswordRequest(string $email): array
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            return ['message' => 'If the email exists, a reset link will be sent.'];
        }

        $resetToken = bin2hex(random_bytes(32));
        $user->setResetToken($resetToken);
        $user->setResetTokenExpiry(new \DateTime('+1 hour'));
        $this->userRepository->saveAction($user);

        $resetLink = sprintf($this->params->get('frontend')
            .'/reset-password/%s', $resetToken);

        try {
            $this->mailerService->sendMail(
                $user->getEmail(),
                'Password Reset Request',
                'email/password/password_reset_request_email.html.twig',
                ['resetLink' => $resetLink]
            );
        } catch (TransportExceptionInterface) {
            throw new MailException();
        }

        return ['message' => 'If the email exists, a reset link will be sent.'];
    }

    /**
     * @throws IncorrectUserConfigurationException
     */
    public function getCurrentUser(): User|JsonResponse
    {
        $token = $this->tokenStorage->getToken();
        $user = $this->getUserByToken($token);
        if (!$user instanceof User) {
            throw new IncorrectUserConfigurationException();
        }

        return $user;
    }

    /**
     * @throws MailException
     */
    public function passwordReset(
        $resetToken,
        $newPassword,
    ): array {
        $user = $this->userRepository->findOneBy(['resetToken' => $resetToken]);
        if (!$user || $user->getResetTokenExpiry() < new \DateTime()) {
            return ['message' => 'Invalid or expired reset token.'];
        }

        $user->setPassword($this->passwordHasher->hashPassword($user, $newPassword));
        $user->setResetToken(null);
        $user->setResetTokenExpiry(null);
        $this->userRepository->saveAction($user);

        try {
            $this->mailerService->sendMail(
                $user->getEmail(),
                'Password Successfully Changed',
                'email/password/password_reset_success_email.html.twig'
            );
        } catch (TransportExceptionInterface) {
            throw new MailException();
        }

        return ['message' => 'Password successfully reset.'];
    }

    public function editAction(User $user, UserEditParams $params, ?UserEditFilesParams $files = null): void
    {
        if ($params->phoneNumber) {
            $user->setPhoneNumber($params->phoneNumber);
        }
        if ($params->address) {
            $user->setAddress($params->address);
        }

        if ($files && $user->getAvatarPath()) {
            $this->fileHelper->deleteFile($user->getAvatarPath(), true);

            $avatarPath = $this->fileHelper->uploadFile($files->avatar, '/images/avatars/', true);
            $user->setAvatarPath($avatarPath);
        }

        $this->userRepository->updateAction($user);
    }
}
