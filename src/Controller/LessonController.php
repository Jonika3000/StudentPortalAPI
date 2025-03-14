<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Services\LessonService;
use App\Services\UserService;
use App\Shared\Response\Exception\AccessDeniedException;
use App\Utils\ExceptionHandleHelper;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: 'api_')]
class LessonController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly LessonService $lessonService,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[OA\Get(
        path: '/api/lesson',
        description: 'Retrieves a list of lessons assigned to the authenticated user (student or teacher).',
        summary: 'Get lessons by user role',
        security: [['Bearer' => []]],
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
    #[Route('/lesson', name: 'lesson_get_all', methods: ['GET'])]
    public function getLessons(): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $lessons = $this->lessonService->getLessonsByUser($user);
            $data = $this->serializer->serialize(
                $lessons,
                'json',
                ['groups' => ['lesson_read', 'subject_read', 'homework_read', 'teacher_read']]
            );

            return new JsonResponse($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }

    #[OA\Get(
        path: '/api/lesson/{id}',
        description: 'Get lesson info by ID',
        summary: 'Get lesson info by ID',
        security: [['Bearer' => []]],
        tags: ['Lesson'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lesson find',
                content: new OA\JsonContent(ref: '#/components/schemas/Lesson')
            ),
            new OA\Response(response: 404, description: 'Lesson not found'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 500, description: 'Server error'),
        ]
    )]
    #[Route('/lesson/{id}', name: 'lesson_get_by_id', methods: ['GET'])]
    public function getLessonById(Lesson $lesson): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            if (!$this->lessonService->userHasAccessToLesson($user, $lesson)) {
                throw new AccessDeniedException();
            }

            $data = $this->serializer->serialize(
                $lesson,
                'json',
                ['groups' => ['lesson_read', 'subject_read', 'homework_read', 'teacher_read', 'user_read']]
            );

            return new JsonResponse($data, Response::HTTP_OK, [], true);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }
}
