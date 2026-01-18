<?php

declare(strict_types=1);

namespace App\Tests\Books\Application\Service;

use App\Books\Application\Dto\BookDto;
use App\Books\Application\Mapper\BookToBookDtoMapperInterface;
use App\Books\Application\Service\GetBook;
use App\Books\Domain\Entity\Book;
use App\Books\Domain\Exception\BookNotFoundException;
use App\Books\Domain\Repository\BookRepositoryInterface;
use App\Books\Domain\ValueObject\Book\BookId;
use App\Books\Domain\ValueObject\Book\BookTitle;
use PHPUnit\Framework\TestCase;

final class GetBookTest extends TestCase
{
    public function testGetBookSuccess(): void
    {
        $repository = $this->createMock(BookRepositoryInterface::class);
        $mapper = $this->createMock(BookToBookDtoMapperInterface::class);
        $bookId = new BookId(1);
        $book = new Book($bookId, new BookTitle('Test Book'), [], []);
        $bookDto = new BookDto(1, 'Test Book', [], []);

        $repository->expects($this->once())
            ->method('findById')
            ->with($bookId)
            ->willReturn($book);

        $mapper->expects($this->once())
            ->method('map')
            ->with($book)
            ->willReturn($bookDto);

        $service = new GetBook($repository, $mapper);
        $result = $service($bookId);

        $this->assertSame($bookDto, $result);
    }

    public function testGetBookNotFound(): void
    {
        $repository = $this->createMock(BookRepositoryInterface::class);
        $mapper = $this->createMock(BookToBookDtoMapperInterface::class);
        $bookId = new BookId(1);

        $repository->expects($this->once())
            ->method('findById')
            ->with($bookId)
            ->willReturn(null);

        $mapper->expects($this->never())
            ->method('map');

        $service = new GetBook($repository, $mapper);

        $this->expectException(BookNotFoundException::class);
        $service($bookId);
    }
}
