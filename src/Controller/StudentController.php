<?php

namespace App\Controller;

use App\Constants\UserRoles;
use App\Encoder\Student\StudentInfoEncoder;
use App\Entity\Student;
use App\Services\StudentService;
use App\Services\UserService;
use App\Utils\ExceptionHandleHelper;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
class StudentController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly StudentService $studentService,
        private readonly StudentInfoEncoder $encoder,
    ) {
    }

    #[OA\Get(
        path: '/api/student/me',
        summary: "Get the authenticated student's details",
        tags: ['Student'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response',
                content: new OA\JsonContent(ref: '#/components/schemas/Student')
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Student not found'),
        ]
    )]
    #[IsGranted(UserRoles::STUDENT)]
    #[Route('/student/me', name: 'student_me', methods: 'GET')]
    public function get(): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $student = $this->studentService->getStudentByUser($user);
            $data = $this->encoder->encode($student);

            return new JsonResponse($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }

    #[OA\Get(
        path: '/api/student/{id}',
        summary: 'Get student details by ID',
        security: [['Bearer' => []]],
        tags: ['Student'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Student ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response',
                content: new OA\JsonContent(ref: '#/components/schemas/Student')
            ),
            new OA\Response(response: 403, description: 'Forbidden - Requires TEACHER role'),
            new OA\Response(response: 404, description: 'Student not found'),
        ]
    )]
    #[IsGranted(UserRoles::TEACHER)]
    #[Route('/student/{id}', name: 'student_get', methods: 'GET')]
    public function getStudentInfo(Student $student): JsonResponse
    {
        $data = $this->encoder->encode($student);

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
