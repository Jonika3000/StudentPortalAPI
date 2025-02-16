<?php

namespace App\Controller;

use App\Constants\UserRoles;
use App\Entity\Classroom;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api', name: 'api_')]
class ClassroomController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[OA\Get(
        path: '/api/classroom/{id}',
        summary: 'Get classroom information',
        security: [['Bearer' => []]],
        tags: ['Classroom'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Classroom ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Classroom information retrieved successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Classroom')
            ),
            new OA\Response(response: 403, description: 'Access denied'),
            new OA\Response(response: 404, description: 'Classroom not found'),
        ]
    )]
    #[IsGranted(UserRoles::TEACHER)]
    #[Route('/classroom/{id}', name: 'classroom_get', methods: ['GET'])]
    public function getClassroomInfo(Classroom $classroom): JsonResponse
    {
        $data = $this->serializer->serialize(
            $classroom,
            'json',
            ['groups' => ['classroom_read', 'user_read', 'teacher_read', 'student_read', 'subject_read', 'lesson_read']]
        );

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}
