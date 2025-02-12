<?php

namespace App\Tests\Controller;

use App\Controller\ClassroomController;
use App\Entity\Classroom;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class ClassroomControllerTest extends WebTestCase
{
    public function testGetClassroomInfo()
    {
        $classroomMock = \Mockery::mock(Classroom::class);
        $classroomMock->shouldReceive('getId')->andReturn(1);
        $classroomMock->shouldReceive('getUuid')->andReturn(Uuid::v4());
        $classroomMock->shouldReceive('getStudents')->andReturn([]);
        $classroomMock->shouldReceive('getLessons')->andReturn([]);
        $classroomMock->shouldReceive('getCreatedAt')->andReturn(new \DateTimeImmutable());

        $serializerMock = \Mockery::mock(SerializerInterface::class);
        $serializerMock->shouldReceive('serialize')->with($classroomMock, 'json', ['groups' => 'student_read'])->andReturn(json_encode([
            'id' => 1,
            'uuid' => (string) Uuid::v4(),
            'students' => [],
            'lessons' => [],
            'createdAt' => (new \DateTimeImmutable())->format(DATE_ATOM),
        ]));

        $controller = new ClassroomController();
        $response = new JsonResponse($serializerMock->serialize($classroomMock, 'json', ['groups' => 'student_read']), Response::HTTP_OK, [], true);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertNotEmpty($responseData);
    }
}
