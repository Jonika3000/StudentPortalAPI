<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    private string $creditals;

    protected function setUp(): void
    {
        parent::setUp();

        $this->creditals = json_encode([
            'username' => 'user@example.com',
            'password' => 'correctpassword',
        ]);
    }

    public function testRegistration(): void
    {
        $client = static::createClient();

        $tempFile = tempnam(sys_get_temp_dir(), 'test').'.jpg';
        $image = imagecreatetruecolor(100, 100); // Create a blank 100x100 image
        imagejpeg($image, $tempFile); // Save the image as a JPEG
        imagedestroy($image);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'test-image.jpg',
            'image/jpeg',
            null,
            true
        );

        $data = [
            'email' => 'user@example.com',
            'password' => 'correctpassword',
            'firstName' => 'John',
            'secondName' => 'Doe',
            'birthday' => '1990-01-01',
            'address' => '123 Street Name',
            'phoneNumber' => '123456789',
            'gender' => 'Male',
        ];

        $client->request(
            'POST',
            '/api/register',
            ['data' => json_encode($data)],
            ['avatar' => $uploadedFile],
            ['CONTENT_TYPE' => 'multipart/form-data']
        );

        $this->assertResponseIsSuccessful();

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Registered Successfully', $responseData['message']);
    }

    public function testSuccessfulLogin(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], $this->creditals);

        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('token', $data);
        $this->assertNotEmpty($data['token']);
    }

    public function testLoginWithInvalidData(): void
    {
        $client = static::createClient();

        $credentials = [
            'username' => 'user@example.com',
            'password' => '',
        ];

        $client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode($credentials));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testGetCurrentUser()
    {
        $client = static::createClient();

        $client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], $this->creditals);

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $data);

        $jwtToken = $data['token'];

        $client->request('GET', '/api/user/me', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.$jwtToken,
        ]);

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('email', $data);
        $this->assertEquals('user@example.com', $data['email']);
    }

    public function testUserEdit()
    {
        $client = static::createClient();

        $client->request('POST', '/api/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], $this->creditals);

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $data);

        $jwtToken = $data['token'];

        $tempFile = tempnam(sys_get_temp_dir(), 'test_avatar').'.jpg';
        $image = imagecreatetruecolor(100, 100);
        imagejpeg($image, $tempFile);
        imagedestroy($image);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'test-avatar.jpg',
            'image/jpeg',
            null,
            true
        );

        $client->request(
            'PATCH',
            '/api/user/update',
            [
                'data' => json_encode(['address' => 'New Address', 'phoneNumber' => '123456789']),
            ],
            [
                'avatar' => $uploadedFile,
            ],
            [
                'HTTP_AUTHORIZATION' => 'Bearer '.$jwtToken,
                'CONTENT_TYPE' => 'multipart/form-data',
            ]
        );

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertEquals('"Success"', $response->getContent());

        unlink($tempFile);
    }
}
