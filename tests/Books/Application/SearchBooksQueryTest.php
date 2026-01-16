<?php

declare(strict_types=1);

namespace App\Tests\Books\Application;

use App\Books\Application\Mapper\BookToBookDtoMapper;
use App\Books\Application\Service\SearchBooks;
use App\Books\Domain\Entity\Book;
use App\Books\Domain\Repository\BookRepositoryInterface;
use App\Books\Domain\ValueObject\Author\Author;
use App\Books\Domain\ValueObject\Author\AuthorName;
use App\Books\Domain\ValueObject\Book\BookId;
use App\Books\Domain\ValueObject\Book\BookTitle;
use App\Books\Domain\ValueObject\Book\Subject;
use PHPUnit\Framework\TestCase;

final class SearchBooksQueryTest extends TestCase
{
    public function testItShouldSearchBooks(): void
    {
        $repository = $this->createMock(BookRepositoryInterface::class);
        $book = new Book(
            id: new BookId(1),
            title: new BookTitle('Test Title'),
            subjects: [new Subject('Subject')],
            authors: [new Author(new AuthorName('Author Name'))]
        );

        $repository->expects($this->once())
            ->method('searchByCriteria')
            ->with('test')
            ->willReturn([$book]);

        $mapper = new BookToBookDtoMapper();
        $query = new SearchBooks($repository, $mapper);
        $response = $query->__invoke('test');

        $this->assertCount(1, $response);
        $this->assertEquals(1, $response[0]->id);
        $this->assertEquals('Test Title', $response[0]->title);
    }
}
