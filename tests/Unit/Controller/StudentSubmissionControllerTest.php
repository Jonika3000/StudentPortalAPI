<?php

namespace App\Tests\Unit\Controller;

use App\Controller\StudentSubmissionController;
use App\Decoder\FileBagDecoder\StudentSubmissionFileBagDecoder;
use App\Decoder\StudentSubmission\StudentSubmissionPostDecoder;
use App\Entity\StudentSubmission;
use App\Entity\User;
use App\Request\StudentSubmission\StudentSubmissionPostRequest;
use App\Services\StudentSubmissionService;
use App\Services\UserService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StudentSubmissionControllerTest extends TestCase
{
    public function testStore(): void
    {
        $studentSubmissionService = $this->createMock(StudentSubmissionService::class);
        $userService = $this->createMock(UserService::class);
        $fileBagDecoder = $this->createMock(StudentSubmissionFileBagDecoder::class);
        $userMock = $this->createMock(User::class);
        $userService->method('getCurrentUser')->willReturn($userMock);
        $controller = new StudentSubmissionController($studentSubmissionService, $userService, $fileBagDecoder);
        $response = $controller->store($this->createMock(StudentSubmissionPostRequest::class), $this->createMock(StudentSubmissionPostDecoder::class));

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $studentSubmissionService = $this->createMock(StudentSubmissionService::class);
        $userService = $this->createMock(UserService::class);
        $fileBagDecoder = $this->createMock(StudentSubmissionFileBagDecoder::class);
        $userMock = $this->createMock(User::class);
        $userService->method('getCurrentUser')->willReturn($userMock);
        $controller = new StudentSubmissionController($studentSubmissionService, $userService, $fileBagDecoder);

        $response = $controller->delete($this->createMock(StudentSubmission::class));

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGet(): void
    {
        $studentSubmissionService = $this->createMock(StudentSubmissionService::class);
        $userService = $this->createMock(UserService::class);
        $fileBagDecoder = $this->createMock(StudentSubmissionFileBagDecoder::class);
        $userMock = $this->createMock(User::class);
        $userService->method('getCurrentUser')->willReturn($userMock);
        $controller = new StudentSubmissionController($studentSubmissionService, $userService, $fileBagDecoder);

        $response = $controller->get($this->createMock(StudentSubmission::class));

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}