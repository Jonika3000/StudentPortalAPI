<?php

namespace App\Controller;

use App\Constants\UserRoles;
use App\Decoder\FileBagDecoder\HomeworkFileBagDecoder;
use App\Decoder\Homework\HomeworkPostDecoder;
use App\Decoder\Homework\HomeworkUpdateDecoder;
use App\Entity\Homework;
use App\Request\Homework\HomeworkPostRequest;
use App\Request\Homework\HomeworkUpdateRequest;
use App\Services\HomeworkService;
use App\Services\UserService;
use App\Utils\ExceptionHandleHelper;
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
    ) {
    }

    #[IsGranted(UserRoles::TEACHER)]
    #[Route('/homework', name: 'homework', methods: ['POST'])]
    public function store(HomeworkPostRequest $request, HomeworkPostDecoder $paramsDecoder): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $params = $paramsDecoder->decode($request);
            $files = $this->fileBagDecoder->decode($request->getFiles());

            return new JsonResponse($this->homeworkService->postAction($params, $user, $files), Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }

    #[IsGranted(UserRoles::TEACHER)]
    #[Route('/homework/{id}', name: 'homework_get_by_id_teacher', methods: ['GET'])]
    public function viewTeacher(Homework $homework): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $this->homeworkService->checkAccessHomeworkTeacher($homework, $user);

            return new JsonResponse($homework, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }

    #[IsGranted(UserRoles::STUDENT)]
    #[Route('/homework/{id}', name: 'homework_get_by_id_student', methods: ['GET'])]
    public function viewStudent(Homework $homework): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $response = $this->homeworkService->getHomeworkStudent($homework, $user);

            return new JsonResponse($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }

    #[IsGranted(UserRoles::TEACHER)]
    #[Route('/homework/{id}', name: 'homework_delete', methods: ['DELETE'])]
    public function remove(Homework $homework): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $this->homeworkService->deleteAction($homework, $user);

            return new JsonResponse('Success', Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }

    #[IsGranted(UserRoles::TEACHER)]
    #[Route('/homework/{id}', name: 'homework_update', methods: ['PATCH'])]
    public function update(Homework $homework, HomeworkUpdateRequest $request, HomeworkUpdateDecoder $homeworkUpdateDecoder): JsonResponse
    {
        try {
            $user = $this->userService->getCurrentUser();
            $params = $homeworkUpdateDecoder->decode($request);
            $files = $this->fileBagDecoder->decode($request->getFiles());

            $updatedHomework = $this->homeworkService->updateAction($homework, $user, $params, $files);

            return new JsonResponse($updatedHomework, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return ExceptionHandleHelper::handleException($exception);
        }
    }
}
