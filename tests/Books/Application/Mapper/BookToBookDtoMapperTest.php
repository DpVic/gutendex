<?php

declare(strict_types=1);

namespace App\Tests\Books\Application\Mapper;

use App\Books\Application\Mapper\BookToBookDtoMapper;
use App\Books\Domain\Entity\Book;
use App\Books\Domain\ValueObject\Author\Author;
use App\Books\Domain\ValueObject\Author\AuthorName;
use App\Books\Domain\ValueObject\Book\BookId;
use App\Books\Domain\ValueObject\Book\BookTitle;
use App\Books\Domain\ValueObject\Book\Subject;
use PHPUnit\Framework\TestCase;

final class BookToBookDtoMapperTest extends TestCase
{
    public function testItMapsDomainBookToDtoAndSerializes(): void
    {
        $book = new Book(
            id: new BookId(1),
            title: new BookTitle('Test Title'),
            subjects: [new Subject('Subject 1'), new Subject('Subject 2')],
            authors: [
                new Author(
                    name: new AuthorName('Author Name')
                ),
            ]
        );

        $mapper = new BookToBookDtoMapper();

        $dto = $mapper->map($book);

        $this->assertSame(1, $dto->id);
        $this->assertSame('Test Title', $dto->title);
        $this->assertSame(['Subject 1', 'Subject 2'], $dto->subjects);
        $this->assertCount(1, $dto->authors);

        $serialized = $dto->jsonSerialize();

        $this->assertSame(1, $serialized['id']);
        $this->assertSame('Test Title', $serialized['title']);
        $this->assertSame(['Subject 1', 'Subject 2'], $serialized['subjects']);

        $this->assertCount(1, $serialized['authors']);
        $this->assertSame('Author Name', $serialized['authors'][0]['name']);
        $this->assertArrayHasKey('birth_year', $serialized['authors'][0]);
        $this->assertArrayHasKey('death_year', $serialized['authors'][0]);
    }
}
