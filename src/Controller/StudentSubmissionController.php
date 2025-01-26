<?php

namespace App\Controller;

use App\Constants\UserRoles;
use App\Decoder\FileBagDecoder\StudentSubmissionFileBagDecoder;
use App\Decoder\StudentSubmission\StudentSubmissionPostDecoder;
use App\Entity\StudentSubmission;
use App\Request\StudentSubmission\StudentSubmissionPostRequest;
use App\Services\StudentSubmissionService;
use App\Services\UserService;
use App\Utils\ExceptionHandleHelper;
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
    ) {
    }

    #[IsGranted(UserRoles::STUDENT)]
    #[Route('/student/submission', name: 'student_submission', methods: ['POST'])]
    public function store(StudentSubmissionPostRequest $request, StudentSubmissionPostDecoder $paramsDecoder): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $params = $paramsDecoder->decode($request);
            $files = $this->fileBagDecoder->decode($request->getFiles());

            return new JsonResponse(
                $this->studentSubmissionService->postAction($params, $user, $files),
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }

    #[IsGranted(UserRoles::STUDENT)]
    #[Route('/student/submission/{id}', name: 'student_submission_delete', methods: ['DELETE'])]
    public function delete(StudentSubmission $studentSubmission): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $this->studentSubmissionService->deleteAction($studentSubmission, $user);

            return new JsonResponse('Success', Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }

    #[IsGranted(UserRoles::USER)]
    #[Route('/student/submission/{id}', name: 'student_submission_delete', methods: ['GET'])]
    public function get(StudentSubmission $studentSubmission): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();

            return new JsonResponse(
                $this->studentSubmissionService->getAction($studentSubmission, $user),
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }
}
