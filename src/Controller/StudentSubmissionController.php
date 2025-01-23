<?php

namespace App\Controller;

use App\Decoder\FileBagDecoder\StudentSubmissionFileBagDecoder;
use App\Decoder\StudentSubmission\StudentSubmissionPostDecoder;
use App\Request\StudentSubmission\StudentSubmissionPostRequest;
use App\Services\StudentSubmissionService;
use App\Services\UserService;
use App\Utils\ExceptionHandleHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class StudentSubmissionController extends AbstractController
{
    public function __construct(
        private readonly StudentSubmissionService $studentSubmissionService,
        private readonly UserService $userService,
        private readonly StudentSubmissionFileBagDecoder $fileBagDecoder,
    ) {
    }

    #[Route('/student/submission', name: 'student_submission', methods: ['POST'])]
    public function store(StudentSubmissionPostRequest $request, StudentSubmissionPostDecoder $paramsDecoder): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $params = $paramsDecoder->decode($request);
            $files = $this->fileBagDecoder->decode($request->getFiles());

            // return new JsonResponse($this->studentSubmissionService->postAction($params, $user, $files), Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }
}
