<?php

declare(strict_types=1);

namespace App\Books\Application\Mapper;

use App\Books\Application\Dto\AuthorDto;
use App\Books\Application\Dto\BookDto;
use App\Books\Domain\Entity\Book;
use App\Books\Domain\ValueObject\Author\Author;
use App\Books\Domain\ValueObject\Book\Subject;

final readonly class BookToBookDtoMapper
{
    public function map(Book $book): BookDto
    {
        return new BookDto(
            id: $book->id->value,
            title: $book->title->value,
            subjects: array_map(
                static fn (Subject $subject): string => $subject->value,
                $book->subjects
            ),
            authors: array_map(
                static fn (Author $author): AuthorDto => new AuthorDto(
                    name: $author->name->value,
                    birthYear: $author->birthYear?->value,
                    deathYear: $author->deathYear?->value,
                ),
                $book->authors
            ),
        );
    }

    /**
     * @param list<Book> $books
     *
     * @return list<BookDto>
     */
    public function mapList(array $books): array
    {
        return array_map($this->map(...), $books);
    }
}
