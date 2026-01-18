<?php

declare(strict_types=1);

namespace App\Tests\Books\Application\Service;

use App\Books\Application\Dto\BookDto;
use App\Books\Application\Mapper\BookToBookDtoMapperInterface;
use App\Books\Application\Service\SearchBooks;
use App\Books\Domain\Entity\Book;
use App\Books\Domain\Repository\BookRepositoryInterface;
use App\Books\Domain\ValueObject\Book\BookId;
use App\Books\Domain\ValueObject\Book\BookTitle;
use PHPUnit\Framework\TestCase;

final class SearchBooksTest extends TestCase
{
    public function testSearchBooks(): void
    {
        $repository = $this->createMock(BookRepositoryInterface::class);
        $mapper = $this->createMock(BookToBookDtoMapperInterface::class);
        $query = 'Test';
        $book = new Book(new BookId(1), new BookTitle('Test Book'), [], []);
        $books = [$book];
        $bookDto = new BookDto(1, 'Test Book', [], []);
        $bookDtos = [$bookDto];

        $repository->expects($this->once())
            ->method('searchByCriteria')
            ->with($query)
            ->willReturn($books);

        $mapper->expects($this->once())
            ->method('mapList')
            ->with($books)
            ->willReturn($bookDtos);

        $service = new SearchBooks($repository, $mapper);
        $result = $service($query);

        $this->assertSame($bookDtos, $result);
    }

    public function testSearchBooksEmpty(): void
    {
        $repository = $this->createMock(BookRepositoryInterface::class);
        $mapper = $this->createMock(BookToBookDtoMapperInterface::class);
        $query = 'Empty';

        $repository->expects($this->once())
            ->method('searchByCriteria')
            ->with($query)
            ->willReturn([]);

        $mapper->expects($this->once())
            ->method('mapList')
            ->with([])
            ->willReturn([]);

        $service = new SearchBooks($repository, $mapper);
        $result = $service($query);

        $this->assertSame([], $result);
    }
}
