<?php

namespace App\Controller;

use App\Constants\UserRoles;
use App\Decoder\Grade\GradePostDecoder;
use App\Decoder\Grade\GradeUpdateDecoder;
use App\Entity\Grade;
use App\Request\Grade\GradePostRequest;
use App\Request\Grade\GradeUpdateRequest;
use App\Services\GradeService;
use App\Services\UserService;
use App\Utils\ExceptionHandleHelper;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
class GradeController extends AbstractController
{
    public function __construct(
        private readonly GradeService $gradeService,
        private readonly UserService $userService,
    ) {
    }

    #[OA\Post(
        path: '/api/grade',
        description: 'Assigns a grade to a student submission',
        summary: 'Create a new grade',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: '#/components/schemas/GradePostRequest')
            )
        ),
        tags: ['Grade'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(type: 'string', example: 'Success')
            ),
        ]
    )]
    #[IsGranted(UserRoles::TEACHER)]
    #[Route('/grade', name: 'grade_post', methods: ['POST'])]
    public function post(GradePostRequest $request, GradePostDecoder $decoder): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();

            $params = $decoder->decode($request);

            $this->gradeService->postAction($user, $params);

            return new JsonResponse('Success', Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }

    #[OA\Patch(
        path: '/api/grade/{id}',
        description: 'Modifies an existing grade',
        summary: 'Update an existing grade',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/GradeUpdateRequest')
        ),
        tags: ['Grade'],
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
                description: 'Success',
                content: new OA\JsonContent(type: 'string', example: 'Success')
            ),
        ]
    )]
    #[IsGranted(UserRoles::TEACHER)]
    #[Route('/grade/{id}', name: 'grade_patch', methods: ['PATCH'])]
    public function update(Grade $grade, GradeUpdateRequest $request, GradeUpdateDecoder $decoder): JsonResponse
    {
        try {
            $params = $decoder->decode($request);

            $this->gradeService->updateAction($grade, $params);

            return new JsonResponse('Success', Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }

    #[OA\Delete(
        path: '/api/grade/{id}',
        description: 'Removes a grade entry',
        summary: 'Delete a grade',
        security: [['bearerAuth' => []]],
        tags: ['Grade'],
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
                description: 'Success',
                content: new OA\JsonContent(type: 'string', example: 'Success')
            ),
        ]
    )]
    #[IsGranted(UserRoles::TEACHER)]
    #[Route('/grade/{id}', name: 'grade_delete', methods: ['DELETE'])]
    public function remove(Grade $grade): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();

            $this->gradeService->deleteAction($user, $grade);

            return new JsonResponse('Success', Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }
}
