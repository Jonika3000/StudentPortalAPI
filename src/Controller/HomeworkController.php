<?php

namespace App\Controller;

use App\Constants\UserRoles;
use App\Decoder\FileBagDecoder\HomeworkFileBagDecoder;
use App\Decoder\Homework\HomeworkPostDecoder;
use App\Decoder\Homework\HomeworkUpdateDecoder;
use App\Encoder\Homework\HomeworkInfoEncoder;
use App\Entity\Homework;
use App\Helper\ExceptionHandlerHelper;
use App\Services\HomeworkService;
use App\Services\UserService;
use App\Shared\Request\Homework\HomeworkPostRequest;
use App\Shared\Request\Homework\HomeworkUpdateRequest;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
class HomeworkController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly HomeworkService $homeworkService,
        private readonly HomeworkFileBagDecoder $fileBagDecoder,
        private readonly ExceptionHandlerHelper $exceptionHandler,
    ) {
    }

    #[OA\Post(
        path: '/api/homework',
        description: 'Creates a new homework assignment',
        summary: 'Create a new homework',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: '#/components/schemas/HomeworkPostRequest')
            )
        ),
        tags: ['Homework'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(type: 'string', example: 'Success')
            ),
        ]
    )]
    #[IsGranted(UserRoles::TEACHER)]
    #[Route('/homework', name: 'homework_post', methods: ['POST'])]
    public function store(HomeworkPostRequest $request, HomeworkPostDecoder $paramsDecoder): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $params = $paramsDecoder->decode($request);
            $files = $this->fileBagDecoder->decode($request->getFiles());
            $this->homeworkService->postAction($params, $user, $files);

            return new JsonResponse('Success', Response::HTTP_CREATED);
        } catch (\Exception $exception) {
            return $this->exceptionHandler->handle($exception);
        }
    }

    #[OA\Get(
        path: '/api/homework/{id}',
        description: 'Retrieve a specific homework entry',
        summary: 'Get homework details',
        security: [['Bearer' => []]],
        tags: ['Homework'],
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
                content: new OA\JsonContent(ref: '#/components/schemas/Homework')
            ),
        ]
    )]
    #[Route('/homework/{id}', name: 'homework_get', methods: ['GET'])]
    public function getHomeworkInfo(Homework $homework, HomeworkInfoEncoder $encoder): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();

            if (in_array(UserRoles::TEACHER, $user->getRoles(), true)) {
                $this->homeworkService->checkAccessHomeworkTeacher($homework, $user);
            }
            if (in_array(UserRoles::STUDENT, $user->getRoles(), true)) {
                $encodedData = $this->homeworkService->getHomeworkStudent($homework, $user);

                return new JsonResponse($encodedData);
            }

            return new JsonResponse($encoder->encode($homework));
        } catch (\Exception $exception) {
            return $this->exceptionHandler->handle($exception);
        }
    }

    #[OA\Delete(
        path: '/api/homework/{id}',
        description: 'Deletes a homework entry, accessible by teachers',
        summary: 'Delete a homework entry',
        security: [['Bearer' => []]],
        tags: ['Homework'],
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
    #[Route('/homework/{id}', name: 'homework_delete', methods: ['DELETE'])]
    public function remove(Homework $homework): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $this->homeworkService->deleteAction($homework, $user);

            return new JsonResponse('Success', Response::HTTP_NO_CONTENT);
        } catch (\Exception $exception) {
            return $this->exceptionHandler->handle($exception);
        }
    }

    #[OA\Post(
        path: '/api/homework/{id}',
        description: 'Updates a homework entry, accessible by teachers',
        summary: 'Update a homework entry',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: '#/components/schemas/HomeworkUpdateRequest'
                )
            )),
        tags: ['Homework'],
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
                content: new OA\JsonContent(ref: '#/components/schemas/Homework')
            ),
        ]
    )]
    #[IsGranted(UserRoles::TEACHER)]
    #[Route('/homework/{id}', name: 'homework_patch', methods: ['POST'])]
    public function update(Homework $homework, HomeworkUpdateRequest $request, HomeworkUpdateDecoder $homeworkUpdateDecoder): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $params = $homeworkUpdateDecoder->decode($request);
            $files = $this->fileBagDecoder->decode($request->getFiles());

            $this->homeworkService->updateAction($homework, $user, $params, $files);

            return new JsonResponse('Success', Response::HTTP_OK);
        } catch (\Exception $exception) {
            return $this->exceptionHandler->handle($exception);
        }
    }
}
