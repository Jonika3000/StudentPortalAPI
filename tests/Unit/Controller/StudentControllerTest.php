<?php

namespace App\Tests\Unit\Controller;

use App\Controller\StudentController;
use App\Entity\Student;
use App\Entity\User;
use App\Services\StudentService;
use App\Services\UserService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class StudentControllerTest extends TestCase
{
    public function testIndex(): void
    {
        $userService = $this->createMock(UserService::class);
        $studentService = $this->createMock(StudentService::class);

        $user = $this->createMock(User::class);
        $student = $this->createMock(Student::class);

        $userService->method('getCurrentUser')->willReturn($user);
        $studentService->method('getStudentByUser')->willReturn($student);

        $controller = new StudentController($userService, $studentService);
        $response = $controller->index();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testFind(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $student = $this->createMock(Student::class);

        $serializer->method('serialize')->willReturn('{}');

        $controller = new StudentController($this->createMock(UserService::class), $this->createMock(StudentService::class));
        $response = $controller->find($student, $serializer);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}