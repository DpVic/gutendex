<?php

declare(strict_types=1);

namespace App\Books\Infrastructure\Repository;

use App\Books\Domain\Entity\Book;
use App\Books\Domain\Repository\BookRepositoryInterface;
use App\Books\Domain\ValueObject\Author\Author;
use App\Books\Domain\ValueObject\Author\AuthorName;
use App\Books\Domain\ValueObject\Book\BookId;
use App\Books\Domain\ValueObject\Book\BookTitle;
use App\Books\Domain\ValueObject\Book\Subject;
use App\Shared\Domain\ValueObject\Year;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @phpstan-type GutendexAuthor array{
 *   name: string,
 *   birth_year?: int|null,
 *   death_year?: int|null
 * }
 * @phpstan-type GutendexBook array{
 *   id: int,
 *   title: string,
 *   authors?: list<GutendexAuthor>,
 *   subjects?: list<string>
 * }
 * @phpstan-type GutendexSearchResponse array{
 *   results: list<GutendexBook>
 * }
 */
final readonly class GutendexBookRepository implements BookRepositoryInterface
{
    private const string BASE_URL = 'https://gutendex.com/books/';

    public function __construct(
        private HttpClientInterface $httpClient,
    ) {
    }

    public function searchByCriteria(string $query): array
    {
        $response = $this->httpClient->request('GET', self::BASE_URL, [
            'query' => ['search' => $query],
        ]);

        /** @var GutendexSearchResponse $data */
        $data = $response->toArray();

        return array_map($this->mapToDomain(...), $data['results']);
    }

    public function findById(BookId $id): ?Book
    {
        try {
            $response = $this->httpClient->request('GET', self::BASE_URL.$id->value);

            if (404 === $response->getStatusCode()) {
                return null;
            }

            /** @var GutendexBook $bookData */
            $bookData = $response->toArray();

            return $this->mapToDomain($bookData);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @param GutendexBook $data
     */
    private function mapToDomain(array $data): Book
    {
        $authors = array_map(
            callback: static fn (array $authorData): Author => new Author(
                name: new AuthorName($authorData['name']),
                birthYear: isset($authorData['birth_year']) ? new Year($authorData['birth_year']) : null,
                deathYear: isset($authorData['death_year']) ? new Year($authorData['death_year']) : null,
            ),
            array: $data['authors'] ?? []
        );
        $subjects = array_map(
            callback: static fn (string $subject): Subject => new Subject($subject),
            array: $data['subjects'] ?? []
        );

        return new Book(
            id: new BookId($data['id']),
            title: new BookTitle($data['title']),
            subjects: $subjects,
            authors: $authors
        );
    }
}
