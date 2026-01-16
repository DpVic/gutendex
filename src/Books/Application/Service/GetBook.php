<?php

declare(strict_types=1);

namespace App\Books\Application\Service;

use App\Books\Application\Dto\BookDto;
use App\Books\Application\Mapper\BookToBookDtoMapperInterface;
use App\Books\Domain\Entity\Book;
use App\Books\Domain\Exception\BookNotFoundException;
use App\Books\Domain\Repository\BookRepositoryInterface;
use App\Books\Domain\ValueObject\Book\BookId;

final readonly class GetBook
{
    public function __construct(
        private BookRepositoryInterface $repository,
        private BookToBookDtoMapperInterface $mapper,
    ) {
    }

    public function __invoke(BookId $id): BookDto
    {
        $book = $this->repository->findById($id);

        if (!$book instanceof Book) {
            throw new BookNotFoundException($id);
        }

        return $this->mapper->map($book);
    }
}
