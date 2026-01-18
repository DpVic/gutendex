<?php

declare(strict_types=1);

namespace App\Tests\Books\Infrastructure\Repository;

use App\Books\Domain\ValueObject\Book\BookId;
use App\Books\Infrastructure\Repository\GutendexBookRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class GutendexBookRepositoryTest extends TestCase
{
    public function testSearchByCriteria(): void
    {
        $mockResponseData = [
            'results' => [
                [
                    'id' => 1,
                    'title' => 'Test Book',
                    'authors' => [
                        ['name' => 'Author Name', 'birth_year' => 1800, 'death_year' => 1900],
                    ],
                    'subjects' => ['Subject 1'],
                ],
            ],
        ];

        $json = json_encode($mockResponseData);
        $this->assertIsString($json);
        $mockResponse = new MockResponse($json);
        $client = new MockHttpClient($mockResponse);
        $repository = new GutendexBookRepository($client);

        $results = $repository->searchByCriteria('query');

        $this->assertCount(1, $results);
        $this->assertSame(1, $results[0]->id->value);
        $this->assertSame('Test Book', $results[0]->title->value);
        $this->assertSame('https://gutendex.com/books/?search=query', $mockResponse->getRequestUrl());
        $this->assertSame('GET', $mockResponse->getRequestMethod());
    }

    public function testFindByIdSuccess(): void
    {
        $mockResponseData = [
            'id' => 1,
            'title' => 'Test Book',
            'authors' => [],
            'subjects' => [],
        ];

        $json = json_encode($mockResponseData);
        $this->assertIsString($json);
        $mockResponse = new MockResponse($json);
        $client = new MockHttpClient($mockResponse);
        $repository = new GutendexBookRepository($client);

        $book = $repository->findById(new BookId(1));

        $this->assertNotNull($book);
        $this->assertSame(1, $book->id->value);
        $this->assertSame('https://gutendex.com/books/1', $mockResponse->getRequestUrl());
    }

    public function testFindByIdNotFound(): void
    {
        $mockResponse = new MockResponse('', ['http_code' => 404]);
        $client = new MockHttpClient($mockResponse);
        $repository = new GutendexBookRepository($client);

        $book = $repository->findById(new BookId(999));

        $this->assertNull($book);
        $this->assertSame('https://gutendex.com/books/999', $mockResponse->getRequestUrl());
    }

    public function testFindByIdExceptionReturnsNull(): void
    {
        $mockResponse = new MockResponse('', ['error' => 'Connection refused']);
        $client = new MockHttpClient($mockResponse);
        $repository = new GutendexBookRepository($client);

        $book = $repository->findById(new BookId(1));

        $this->assertNull($book);
    }
}
