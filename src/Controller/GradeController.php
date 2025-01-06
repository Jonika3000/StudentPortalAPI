<?php

namespace App\Controller;

use App\Constants\UserRoles;
use App\Decoder\Grade\GradePostDecoder;
use App\Entity\Grade;
use App\Request\Grade\GradePostRequest;
use App\Services\GradeService;
use App\Services\UserService;
use App\Utils\ExceptionHandleHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class GradeController extends AbstractController
{
    public function __construct(
        private readonly GradeService $gradeService,
        private readonly UserService $userService,
    ) {
    }

    #[IsGranted(UserRoles::TEACHER)]
    #[Route('/api/grade', name: 'app_grade_post', methods: ['POST'])]
    public function post(GradePostRequest $request, GradePostDecoder $decoder): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();

            $params = $decoder->decode($request);

            $response = $this->gradeService->postAction($user, $params);

            return new JsonResponse($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }

    #[IsGranted(UserRoles::TEACHER)]
    #[Route('/api/grade/{id}', name: 'app_grade_delete', methods: ['DELETE'])]
    public function remove(Grade $grade): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();

            $response = $this->gradeService->deleteAction($user, $grade);

            return new JsonResponse($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }
}
