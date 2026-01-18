<?php

declare(strict_types=1);

namespace App\Tests\Books\Infrastructure\Repository;

use App\Books\Domain\Entity\Book;
use App\Books\Domain\Repository\BookRepositoryInterface;
use App\Books\Domain\ValueObject\Book\BookId;
use App\Books\Domain\ValueObject\Book\BookTitle;
use App\Books\Infrastructure\Repository\CachedBookRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CachedBookRepositoryTest extends TestCase
{
    public function testSearchByCriteriaUsesCache(): void
    {
        $delegate = $this->createMock(BookRepositoryInterface::class);
        $cache = $this->createMock(CacheInterface::class);
        $query = 'test query';
        $books = [new Book(new BookId(1), new BookTitle('Test'), [], [])];

        $cache->expects($this->once())
            ->method('get')
            ->with($this->callback(fn ($key) => str_contains((string) $key, CachedBookRepository::SEARCH_CACHE_PREFIX)))
            ->willReturnCallback(fn ($key, $callback) => $callback($this->createMock(ItemInterface::class)));

        $delegate->expects($this->once())
            ->method('searchByCriteria')
            ->with($query)
            ->willReturn($books);

        $repository = new CachedBookRepository($delegate, $cache);
        $result = $repository->searchByCriteria($query);

        $this->assertSame($books, $result);
    }

    public function testFindByIdUsesCache(): void
    {
        $delegate = $this->createMock(BookRepositoryInterface::class);
        $cache = $this->createMock(CacheInterface::class);
        $bookId = new BookId(1);
        $book = new Book($bookId, new BookTitle('Test'), [], []);

        $cache->expects($this->once())
            ->method('get')
            ->with(CachedBookRepository::ID_CACHE_PREFIX.$bookId->value)
            ->willReturnCallback(fn ($key, $callback) => $callback($this->createMock(ItemInterface::class)));

        $delegate->expects($this->once())
            ->method('findById')
            ->with($bookId)
            ->willReturn($book);

        $repository = new CachedBookRepository($delegate, $cache);
        $result = $repository->findById($bookId);

        $this->assertSame($book, $result);
    }
}
