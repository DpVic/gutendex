<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

final class BookContext implements Context
{
    private ?Response $response = null;

    public function __construct(
        private readonly KernelInterface $kernel,
    ) {
    }

    /**
     * @When I send a GET request to :path
     */
    public function iSendAGetRequestTo(string $path): void
    {
        $this->response = $this->kernel->handle(Request::create($path, Request::METHOD_GET));
    }

    /**
     * @Then the response status code should be :expectedStatusCode
     */
    public function theResponseStatusCodeShouldBe(int $expectedStatusCode): void
    {
        Assert::assertNotNull($this->response, 'No response received');
        Assert::assertSame($expectedStatusCode, $this->response->getStatusCode());
    }

    /**
     * @Then the response should be in JSON
     */
    public function theResponseShouldBeInJson(): void
    {
        Assert::assertNotNull($this->response, 'No response received');
        Assert::assertStringContainsString('application/json', $this->response->headers->get('Content-Type') ?? '');
    }

    /**
     * @Then the response should contain a book with title :title
     */
    public function theResponseShouldContainABookWithTitle(string $title): void
    {
        $data = $this->getResponseData();
        Assert::assertArrayHasKey('title', $data);
        Assert::assertSame($title, $data['title']);
    }

    /**
     * @Then the response should contain an error message :message
     */
    public function theResponseShouldContainAnErrorMessage(string $message): void
    {
        $data = $this->getResponseData();
        Assert::assertArrayHasKey('error', $data);
        Assert::assertSame($message, $data['error']);
    }

    /**
     * @Then the response should contain a list of books
     */
    public function theResponseShouldContainAListOfBooks(): void
    {
        $this->getResponseData();
    }

    /**
     * @Then the first book should have title :title
     */
    public function theFirstBookShouldHaveTitle(string $title): void
    {
        $data = $this->getResponseData();
        Assert::assertNotEmpty($data);
        $firstBook = $data[0];
        Assert::assertIsArray($firstBook);
        Assert::assertArrayHasKey('title', $firstBook);
        Assert::assertSame($title, $firstBook['title']);
    }

    /**
     * @Then the response should be an empty list
     */
    public function theResponseShouldBeAnEmptyList(): void
    {
        $data = $this->getResponseData();
        Assert::assertEmpty($data);
    }

    /**
     * @return array<mixed>
     */
    private function getResponseData(): array
    {
        Assert::assertNotNull($this->response, 'No response received');
        $content = $this->response->getContent();
        Assert::assertIsString($content);

        $data = json_decode($content, true);
        Assert::assertIsArray($data, 'Response content is not a valid JSON array');

        return $data;
    }
}
