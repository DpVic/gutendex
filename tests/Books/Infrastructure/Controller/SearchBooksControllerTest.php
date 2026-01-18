<?php

declare(strict_types=1);

namespace App\Tests\Books\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class SearchBooksControllerTest extends WebTestCase
{
    public function testSearchBooks(): void
    {
        $client = self::createClient();
        $client->request(Request::METHOD_GET, '/api/books', ['search' => 'Shakespeare']);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $responseData = json_decode($content, true);
        $this->assertIsArray($responseData);

        if (count($responseData) > 0) {
            $book = $responseData[0];
            $this->assertIsArray($book);
            $this->assertArrayHasKey('id', $book);
            $this->assertArrayHasKey('title', $book);
            $this->assertArrayHasKey('authors', $book);
            $this->assertArrayHasKey('subjects', $book);
        }
    }

    public function testSearchBooksEmpty(): void
    {
        $client = self::createClient();
        $client->request(Request::METHOD_GET, '/api/books');

        $this->assertResponseIsSuccessful();
        $content = $client->getResponse()->getContent();
        $this->assertIsString($content);
        $responseData = json_decode($content, true);
        $this->assertIsArray($responseData);
    }
}
