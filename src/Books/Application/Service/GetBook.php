<?php

declare(strict_types=1);

namespace App\Books\Application\Service;

use App\Books\Domain\Entity\Book;
use App\Books\Domain\Exception\BookNotFoundException;
use App\Books\Domain\Repository\BookRepositoryInterface;
use App\Books\Domain\ValueObject\Book\BookId;

final readonly class GetBook
{
    public function __construct(
        private BookRepositoryInterface $repository,
    ) {
    }

    public function __invoke(BookId $id): Book
    {
        $book = $this->repository->findById($id);

        if (!$book instanceof Book) {
            throw new BookNotFoundException($id);
        }

        return $book;
    }
}
