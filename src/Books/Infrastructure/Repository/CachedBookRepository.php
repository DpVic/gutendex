<?php

declare(strict_types=1);

namespace App\Books\Infrastructure\Repository;

use App\Books\Domain\Entity\Book;
use App\Books\Domain\Repository\BookRepositoryInterface;
use App\Books\Domain\ValueObject\Book\BookId;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final readonly class CachedBookRepository implements BookRepositoryInterface
{
    public const string SEARCH_CACHE_PREFIX = 'book_search_';
    public const string ID_CACHE_PREFIX = 'book_id_';
    private const int TTL = 3600;

    public function __construct(
        private BookRepositoryInterface $delegate,
        private CacheInterface $bookCache,
    ) {
    }

    public function searchByCriteria(string $query): array
    {
        $key = self::SEARCH_CACHE_PREFIX.md5($query);

        return $this->bookCache->get($key, function (ItemInterface $item) use ($query) {
            $item->expiresAfter(self::TTL);

            return $this->delegate->searchByCriteria($query);
        });
    }

    public function findById(BookId $id): ?Book
    {
        $key = self::ID_CACHE_PREFIX.$id->value;

        return $this->bookCache->get($key, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(self::TTL);

            return $this->delegate->findById($id);
        });
    }
}
