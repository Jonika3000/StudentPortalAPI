<?php

namespace App\Tests\Unit\Controller;

use App\Controller\HomeworkController;
use App\Decoder\FileBagDecoder\HomeworkFileBagDecoder;
use App\Decoder\Homework\HomeworkPostDecoder;
use App\Decoder\Homework\HomeworkUpdateDecoder;
use App\Entity\Homework;
use App\Entity\User;
use App\Services\HomeworkService;
use App\Services\UserService;
use App\Shared\Request\Homework\HomeworkPostRequest;
use App\Shared\Request\Homework\HomeworkUpdateRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HomeworkControllerTest extends TestCase
{
    public function testStore(): void
    {
        $homeworkService = $this->createMock(HomeworkService::class);
        $userService = $this->createMock(UserService::class);
        $decoder = $this->createMock(HomeworkPostDecoder::class);
        $fileBagDecoder = $this->createMock(HomeworkFileBagDecoder::class);
        $request = $this->createMock(HomeworkPostRequest::class);
        $userMock = $this->createMock(User::class);
        $userService->method('getCurrentUser')->willReturn($userMock);
        $controller = new HomeworkController($userService, $homeworkService, $fileBagDecoder);

        $response = $controller->store($request, $decoder);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $homeworkService = $this->createMock(HomeworkService::class);
        $userService = $this->createMock(UserService::class);
        $decoder = $this->createMock(HomeworkUpdateDecoder::class);
        $fileBagDecoder = $this->createMock(HomeworkFileBagDecoder::class);
        $request = $this->createMock(HomeworkUpdateRequest::class);
        $homework = $this->createMock(Homework::class);
        $userMock = $this->createMock(User::class);
        $userService->method('getCurrentUser')->willReturn($userMock);
        $controller = new HomeworkController($userService, $homeworkService, $fileBagDecoder);

        $response = $controller->update($homework, $request, $decoder);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
