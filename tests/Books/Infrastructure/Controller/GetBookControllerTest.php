<?php

declare(strict_types=1);

namespace App\Tests\Books\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetBookControllerTest extends WebTestCase
{
    public function testGetBookSuccess(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/api/books/1');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $responseData = json_decode($content, true);
        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals(1, $responseData['id']);
        $this->assertArrayHasKey('title', $responseData);
        $this->assertEquals('The King James Bible', $responseData['title']);
        $this->assertArrayHasKey('authors', $responseData);
        $this->assertArrayHasKey('subjects', $responseData);
    }

    public function testGetBookNotFound(): void
    {
        $client = self::createClient();
        $client->request(Request::METHOD_GET, '/api/books/99999999');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $responseData = json_decode($content, true);
        $this->assertEquals(['error' => 'Book not found'], $responseData);
    }
}
