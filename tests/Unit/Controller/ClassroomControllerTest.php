<?php

namespace App\Tests\Unit\Controller;

use App\Controller\ClassroomController;
use App\Entity\Classroom;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ClassroomControllerTest extends TestCase
{
    public function testGetClassroomInfo(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $classroom = $this->createMock(Classroom::class);
        $controller = new ClassroomController($serializer);

        $response = $controller->getClassroomInfo($classroom);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

}
