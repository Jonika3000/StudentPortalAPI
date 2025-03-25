<?php

namespace App\Controller;

use App\Constants\UserRoles;
use App\Dto\Request\StudentSubmission\StudentSubmissionPostRequest;
use App\Dto\Request\StudentSubmission\StudentSubmissionUpdateRequest;
use App\Entity\StudentSubmission;
use App\Serializer\Decoder\FileBagDecoder\StudentSubmissionFileBagDecoder;
use App\Serializer\Decoder\StudentSubmission\StudentSubmissionPostDecoder;
use App\Serializer\Decoder\StudentSubmission\StudentSubmissionUpdateDecoder;
use App\Serializer\Encoder\StudentSubmission\StudentSubmissionInfoEncoder;
use App\Services\StudentSubmissionService;
use App\Services\UserService;
use App\Support\Helper\ExceptionHandlerHelper;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
class StudentSubmissionController extends AbstractController
{
    public function __construct(
        private readonly StudentSubmissionService $studentSubmissionService,
        private readonly UserService $userService,
        private readonly StudentSubmissionFileBagDecoder $fileBagDecoder,
        private readonly ExceptionHandlerHelper $exceptionHandler,
    ) {
    }

    #[OA\Post(
        path: '/api/student/submission',
        description: 'Create a new student submission',
        summary: 'Create a student submission',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: '#/components/schemas/StudentSubmissionPostRequest')
            )
        ),
        tags: ['Student Submission'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(ref: '#/components/schemas/StudentSubmission', type: 'object')
            ),
            new OA\Response(
                response: 400,
                description: 'Validation error',
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
            ),
        ]
    )]
    #[IsGranted(UserRoles::STUDENT)]
    #[Route('/student/submission', name: 'student_submission_post', methods: ['POST'])]
    public function store(StudentSubmissionPostRequest $request, StudentSubmissionPostDecoder $paramsDecoder): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $params = $paramsDecoder->decode($request);
            $files = $this->fileBagDecoder->decode($request->getFiles());
            $this->studentSubmissionService->postAction($params, $user, $files);

            return new JsonResponse(
                'Success',
                Response::HTTP_CREATED
            );
        } catch (\Exception $exception) {
            return $this->exceptionHandler->handle($exception);
        }
    }

    #[OA\Delete(
        path: '/api/student/submission/{id}',
        description: 'Delete a student submission',
        summary: 'Delete a submission',
        security: [['Bearer' => []]],
        tags: ['Student Submission'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the submission',
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
            new OA\Response(
                response: 404,
                description: 'Submission not found',
            ),
        ]
    )]
    #[IsGranted(UserRoles::STUDENT)]
    #[Route('/student/submission/{id}', name: 'student_submission_delete', methods: ['DELETE'])]
    public function delete(StudentSubmission $studentSubmission): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $this->studentSubmissionService->deleteAction($studentSubmission, $user);

            return new JsonResponse([], Response::HTTP_NO_CONTENT);
        } catch (\Exception $exception) {
            return $this->exceptionHandler->handle($exception);
        }
    }

    #[OA\Get(
        path: '/api/student/submission/{id}',
        description: 'Get a student submission',
        summary: 'Get submission details',
        security: [['Bearer' => []]],
        tags: ['Student Submission'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the submission',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(ref: '#/components/schemas/StudentSubmission', type: 'object')
            ),
            new OA\Response(
                response: 404,
                description: 'Submission not found',
            ),
        ]
    )]
    #[IsGranted(UserRoles::USER)]
    #[Route('/student/submission/{id}', name: 'student_submission_get', methods: ['GET'])]
    public function getStudentSubmissionInfo(
        StudentSubmission $studentSubmission,
        StudentSubmissionInfoEncoder $encoder,
    ): JsonResponse {
        try {
            $user = $this->userService->getCurrentUser();
            $studentSubmission = $this->studentSubmissionService->getByUser($studentSubmission, $user);
            $data = $encoder->encode($studentSubmission);

            return new JsonResponse(
                $data,
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return $this->exceptionHandler->handle($exception);
        }
    }

    #[OA\Post(
        path: '/api/student/submission/{id}',
        description: 'Update a student submission',
        summary: 'Update a submission',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(ref: '#/components/schemas/StudentSubmissionUpdateRequest')
            )
        ),
        tags: ['Student Submission'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'The ID of the submission',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(ref: '#/components/schemas/StudentSubmission', type: 'object')
            ),
            new OA\Response(
                response: 400,
                description: 'Validation error',
            ),
            new OA\Response(
                response: 404,
                description: 'Submission not found',
            ),
        ]
    )]
    #[IsGranted(UserRoles::STUDENT)]
    #[Route('/student/submission/{id}', name: 'student_submission_update', methods: ['POST'])]
    public function update(
        StudentSubmission $studentSubmission,
        StudentSubmissionUpdateRequest $request,
        StudentSubmissionUpdateDecoder $paramsDecoder,
    ): JsonResponse {
        try {
            $user = $this->userService->getCurrentUser();
            $params = $paramsDecoder->decode($request);
            $files = $this->fileBagDecoder->decode($request->getFiles());
            $this->studentSubmissionService->updateAction($studentSubmission, $params, $user, $files);

            return new JsonResponse(
                'Success',
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return $this->exceptionHandler->handle($exception);
        }
    }
}
