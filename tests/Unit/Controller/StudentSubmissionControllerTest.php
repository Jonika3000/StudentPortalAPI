<?php

namespace App\Tests\Unit\Controller;

use App\Controller\StudentSubmissionController;
use App\Entity\StudentSubmission;
use App\Entity\User;
use App\Serializer\Decoder\FileBagDecoder\StudentSubmissionFileBagDecoder;
use App\Serializer\Decoder\StudentSubmission\StudentSubmissionPostDecoder;
use App\Services\StudentSubmissionService;
use App\Services\UserService;
use App\Shared\Request\StudentSubmission\StudentSubmissionPostRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class StudentSubmissionControllerTest extends TestCase
{
    public function testStore(): void
    {
        $studentSubmissionService = $this->createMock(StudentSubmissionService::class);
        $userService = $this->createMock(UserService::class);
        $fileBagDecoder = $this->createMock(StudentSubmissionFileBagDecoder::class);
        $serializer = $this->createMock(SerializerInterface::class);

        $userMock = $this->createMock(User::class);
        $userService->method('getCurrentUser')->willReturn($userMock);

        $controller = new StudentSubmissionController($studentSubmissionService, $userService, $fileBagDecoder, $serializer);
        $response = $controller->store($this->createMock(StudentSubmissionPostRequest::class), $this->createMock(StudentSubmissionPostDecoder::class));

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $studentSubmissionService = $this->createMock(StudentSubmissionService::class);
        $userService = $this->createMock(UserService::class);
        $fileBagDecoder = $this->createMock(StudentSubmissionFileBagDecoder::class);
        $serializer = $this->createMock(SerializerInterface::class);

        $userMock = $this->createMock(User::class);
        $userService->method('getCurrentUser')->willReturn($userMock);

        $controller = new StudentSubmissionController($studentSubmissionService, $userService, $fileBagDecoder, $serializer);

        $response = $controller->delete($this->createMock(StudentSubmission::class));

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGet(): void
    {
        $studentSubmissionService = $this->createMock(StudentSubmissionService::class);
        $userService = $this->createMock(UserService::class);
        $fileBagDecoder = $this->createMock(StudentSubmissionFileBagDecoder::class);
        $serializer = $this->createMock(SerializerInterface::class);

        $userMock = $this->createMock(User::class);
        $userService->method('getCurrentUser')->willReturn($userMock);

        $controller = new StudentSubmissionController($studentSubmissionService, $userService, $fileBagDecoder, $serializer);

        $response = $controller->get($this->createMock(StudentSubmission::class));

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}