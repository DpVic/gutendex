<?php

declare(strict_types=1);

namespace App\Books\Application\Dto;

/**
 * @phpstan-type BookShape array{
 *    id: int,
 *    title: string,
 *    authors?: list<AuthorDto>,
 *    subjects?: list<string>
 *  }
 */
final readonly class BookDto implements \JsonSerializable
{
    /**
     * @param list<string>    $subjects
     * @param list<AuthorDto> $authors
     */
    public function __construct(
        public int $id,
        public string $title,
        public array $subjects,
        public array $authors,
    ) {
    }

    /**
     * @return BookShape
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subjects' => $this->subjects,
            'authors' => $this->authors,
        ];
    }
}
