<?php

namespace App\Controller;

use App\Decoder\FileBagDecoder\RegisterFileBagDecoder;
use App\Decoder\FileBagDecoder\UserEditFileBagDecoder;
use App\Decoder\Password\PasswordResetDecoder;
use App\Decoder\Password\PasswordResetRequestDecoder;
use App\Decoder\User\RegisterRequestDecoder;
use App\Decoder\User\UserEditRequestDecoder;
use App\Request\Password\PasswordResetRequest;
use App\Request\Password\PasswordResetRequestRequest;
use App\Request\User\RegisterRequest;
use App\Request\User\UserEditRequest;
use App\Services\UserService;
use App\Utils\ExceptionHandleHelper;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: 'api_')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly UserService $userService,
        private readonly RegisterRequestDecoder $registerRequestDecoder,
        private readonly RegisterFileBagDecoder $registerFileBagDecoder,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[OA\Get(
        path: '/api/user/me',
        summary: 'Get the current authenticated user\'s details.',
        security: [['Bearer' => []]],
        tags: ['User'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(ref: '#/components/schemas/User', type: 'object')
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized. The user must be authenticated.'
            ),
        ]
    )]
    #[Route('/user/me', name: 'user_me', methods: 'GET')]
    public function get(): JsonResponse
    {
        $token = $this->tokenStorage->getToken();
        $user = $this->userService->getUserByToken($token);
        $data = $this->serializer->serialize($user, 'json', ['groups' => 'user_read']);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[OA\Post(
        path: '/api/password-reset-request',
        summary: 'Request a password reset link.',
        requestBody: new OA\RequestBody(
            description: 'Email of the user requesting a password reset.',
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/PasswordResetRequestRequest')
        ),
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Password reset link sent successfully.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'If the email exists, a reset link will be sent.'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid email format or missing field.'
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.'
            ),
        ]
    )]
    #[Route('/password-reset-request', name: 'password_reset_request', methods: ['POST'])]
    public function passwordResetRequest(
        PasswordResetRequestRequest $request,
        PasswordResetRequestDecoder $passwordResetDecoder,
    ): JsonResponse {
        $params = $passwordResetDecoder->decode($request);

        try {
            return new JsonResponse($this->userService->resetPasswordRequest($params->email), Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }

    #[OA\Post(
        path: '/api/password-reset',
        summary: 'Reset the user\'s password using a reset token.',
        requestBody: new OA\RequestBody(
            description: 'Details for resetting the password.',
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/PasswordResetRequest')
        ),
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Password successfully reset.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Password successfully reset.'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid or expired reset token.'
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error.'
            ),
        ]
    )]
    #[Route('/password-reset', name: 'password_reset', methods: ['POST'])]
    public function passwordReset(
        PasswordResetRequest $request,
        PasswordResetDecoder $passwordResetDecoder,
    ): JsonResponse {
        $params = $passwordResetDecoder->decode($request);
        try {
            return new JsonResponse($this->userService->passwordReset($params->resetToken, $params->newPassword), Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }

    #[OA\Post(
        path: '/api/user/update',
        description: 'Allows an authenticated user to update their profile information, including address, phone number, and avatar.',
        summary: 'Update user information',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: '#/components/schemas/UserEditRequest')
            )
        ),
        tags: ['User'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Success'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(response: 400, description: 'Invalid request parameters.'),
            new OA\Response(response: 401, description: 'Unauthorized access.'),
            new OA\Response(response: 500, description: 'Internal server error.'),
        ]
    )]
    #[Route('/user/update', name: 'user_update', methods: ['POST'])]
    public function edit(
        UserEditRequest $request,
        UserEditFileBagDecoder $fileBagDecoder,
        UserEditRequestDecoder $requestDecoder,
    ): JsonResponse {
        try {
            $user = $this->userService->getCurrentUser();
            $files = $fileBagDecoder->decode($request->getFiles());
            $params = $requestDecoder->decode($request);
            $this->userService->editAction($user, $params, $files);

            return new JsonResponse('Success', Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }

    #[OA\Post(
        path: '/api/register',
        description: 'Endpoint for user registration.',
        summary: 'Register a new user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: '#/components/schemas/RegisterRequest')
            )
        ),
        tags: ['User'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User registered successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Registered Successfully'
                        ),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Validation error',
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error',
            ),
        ]
    )]
    #[Route('/register', name: 'register', methods: 'POST')]
    public function register(RegisterRequest $request): JsonResponse
    {
        $files = $this->registerFileBagDecoder->decode($request->getFiles());
        $params = $this->registerRequestDecoder->decode($request);
        $this->userService->postAction($params, $files);

        return new JsonResponse(['message' => 'Registered Successfully']);
    }
}
