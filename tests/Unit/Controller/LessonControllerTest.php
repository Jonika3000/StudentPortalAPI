<?php

namespace App\Tests\Unit\Controller;

use App\Entity\User;
use App\Controller\LessonController;
use App\Services\LessonService;
use App\Services\UserService;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LessonControllerTest extends TestCase
{
    public function testGetByStudent(): void
    {
        $userService = $this->createMock(UserService::class);
        $lessonService = $this->createMock(LessonService::class);

        $userMock = $this->createMock(User::class);
        $lessonsMock = new ArrayCollection([]);

        $userService->method('getCurrentUser')->willReturn($userMock);
        $lessonService->method('getLessonsByStudent')->willReturn($lessonsMock);

        $controller = new LessonController($userService, $lessonService);
        $response = $controller->getByStudent();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}