<?php

namespace App\Tests\Unit\Controller;

use App\Controller\GradeController;
use App\Decoder\Grade\GradePostDecoder;
use App\Decoder\Grade\GradeUpdateDecoder;
use App\Entity\Grade;
use App\Entity\User;
use App\Request\Grade\GradePostRequest;
use App\Request\Grade\GradeUpdateRequest;
use App\Services\GradeService;
use App\Services\UserService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GradeControllerTest extends TestCase
{
    public function testPost(): void
    {
        $gradeService = $this->createMock(GradeService::class);
        $userService = $this->createMock(UserService::class);
        $decoder = $this->createMock(GradePostDecoder::class);
        $request = $this->createMock(GradePostRequest::class);
        $userMock = $this->createMock(User::class);
        $userService->method('getCurrentUser')->willReturn($userMock);

        $controller = new GradeController($gradeService, $userService);

        $response = $controller->post($request, $decoder);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $gradeService = $this->createMock(GradeService::class);
        $userService = $this->createMock(UserService::class);
        $decoder = $this->createMock(GradeUpdateDecoder::class);
        $request = $this->createMock(GradeUpdateRequest::class);
        $grade = $this->createMock(Grade::class);

        $controller = new GradeController($gradeService, $userService);

        $response = $controller->update($grade, $request, $decoder);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testRemove(): void
    {
        $gradeService = $this->createMock(GradeService::class);
        $userService = $this->createMock(UserService::class);
        $grade = $this->createMock(Grade::class);
        $userMock = $this->createMock(User::class);
        $userService->method('getCurrentUser')->willReturn($userMock);

        $controller = new GradeController($gradeService, $userService);

        $response = $controller->remove($grade);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
