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

#[Route('/api', name: 'api_')]
class ClassroomController extends AbstractController
{
    public function __construct(
    ) {
    }

    /**
     * Get classroom information by ID (only for teachers).
     */
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
    #[Route('/classroom/{id}', name: 'classroom_teacher', methods: ['GET'])]
    public function getClassroomInfo(Classroom $classroom): JsonResponse
    {
        return new JsonResponse($classroom, Response::HTTP_OK);
    }
}
