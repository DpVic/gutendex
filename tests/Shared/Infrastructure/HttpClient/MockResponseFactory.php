<?php

declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\HttpClient;

use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class MockResponseFactory
{
    /**
     * @param array<string, mixed> $options
     */
    public function __invoke(string $method, string $url, array $options): ResponseInterface
    {
        if (str_ends_with($url, '/api/books/1') || str_ends_with($url, '/books/1')) {
            $json = json_encode([
                'id' => 1,
                'title' => 'The King James Bible',
                'authors' => [['name' => 'Anonymous']],
                'subjects' => ['English Bible'],
            ]);

            return new MockResponse($json ?: '', ['http_code' => 200, 'response_headers' => ['Content-Type' => 'application/json']]);
        }

        if (str_contains($url, '/books/99999999')) {
            return new MockResponse('', ['http_code' => 404]);
        }

        if (str_contains($url, 'search=Shakespeare')) {
            $json = json_encode([
                'results' => [
                    [
                        'id' => 100,
                        'title' => 'Hamlet',
                        'authors' => [['name' => 'Shakespeare, William']],
                        'subjects' => ['Tragedy'],
                    ],
                ],
            ]);

            return new MockResponse($json ?: '', ['http_code' => 200, 'response_headers' => ['Content-Type' => 'application/json']]);
        }

        if (str_contains($url, '/books/')) {
            $json = json_encode([
                'results' => [],
            ]);

            return new MockResponse($json ?: '', ['http_code' => 200, 'response_headers' => ['Content-Type' => 'application/json']]);
        }

        return new MockResponse('', ['http_code' => 404]);
    }
}
