<?php

declare(strict_types=1);

namespace App\Tests\Books\Application;

use App\Books\Application\Dto\BookDto;
use App\Books\Application\Mapper\BookToBookDtoMapperInterface;
use App\Books\Application\Service\SearchBooks;
use App\Books\Domain\Entity\Book;
use App\Books\Domain\Repository\BookRepositoryInterface;
use App\Books\Domain\ValueObject\Book\BookId;
use App\Books\Domain\ValueObject\Book\BookTitle;
use PHPUnit\Framework\TestCase;

final class SearchBooksQueryTest extends TestCase
{
    public function testItShouldSearchBooks(): void
    {
        $repository = $this->createMock(BookRepositoryInterface::class);
        $mapper = $this->createMock(BookToBookDtoMapperInterface::class);

        $books = [new Book(
            id: new BookId(1),
            title: new BookTitle('Any'),
            subjects: [],
            authors: []
        )];

        $dtos = [
            new BookDto(
                id: 1,
                title: 'Test Title',
                subjects: ['Subject'],
                authors: []
            ),
        ];

        $repository->expects($this->once())
            ->method('searchByCriteria')
            ->with('test')
            ->willReturn($books);

        $mapper->expects($this->once())
            ->method('mapList')
            ->with($books)
            ->willReturn($dtos);

        $query = new SearchBooks($repository, $mapper);

        $response = ($query)('test');

        $this->assertSame($dtos, $response);
    }
}
