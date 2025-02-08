<?php

namespace App\Controller;

use App\Decoder\FileBagDecoder\RegisterFileBagDecoder;
use App\Decoder\User\RegisterRequestDecoder;
use App\Request\User\RegisterRequest;
use App\Services\UserService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

// @TODO: REPLACE REGISTER LOGIC TO USER CONTROLLER
#[Route('/api', name: 'api_')]
class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly RegisterRequestDecoder $registerRequestDecoder,
        private readonly RegisterFileBagDecoder $registerFileBagDecoder,
    ) {
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
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Registered Successfully'
                        ),
                    ]
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
    public function index(RegisterRequest $request): JsonResponse
    {
        $files = $this->registerFileBagDecoder->decode($request->getFiles());
        $params = $this->registerRequestDecoder->decode($request);
        $this->userService->postAction($params, $files);

        return new JsonResponse(['message' => 'Registered Successfully']);
    }
}
