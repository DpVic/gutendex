<?php

declare(strict_types=1);

namespace App\Books\Domain\Repository;

use App\Books\Domain\Entity\Book;
use App\Books\Domain\ValueObject\Book\BookId;

interface BookRepositoryInterface
{
    /**
     * @return list<Book>
     */
    public function searchByCriteria(string $query): array;

    public function findById(BookId $id): ?Book;
}
