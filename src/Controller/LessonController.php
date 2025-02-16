<?php

namespace App\Controller;

use App\Services\LessonService;
use App\Services\UserService;
use App\Utils\ExceptionHandleHelper;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class LessonController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly LessonService $lessonService,
    ) {
    }

    #[OA\Get(
        path: '/api/lesson',
        description: 'Retrieves a list of lessons assigned to the authenticated student.',
        summary: 'Get lessons by student',
        security: [['bearerAuth' => []]],
        tags: ['Lesson'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful retrieval of lessons',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Lesson'))
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    #[Route('/lesson', name: 'lesson_get', methods: ['GET'])]
    public function getByStudent(): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();

            return new JsonResponse($this->lessonService->getLessonsByStudent($user), Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }
}
