<?php

declare(strict_types=1);

namespace App\Books\Application\Service;

use App\Books\Application\Dto\BookDto;
use App\Books\Application\Mapper\BookToBookDtoMapper;
use App\Books\Domain\Repository\BookRepositoryInterface;

final readonly class SearchBooks
{
    public function __construct(
        private BookRepositoryInterface $repository,
        private BookToBookDtoMapper $mapper,
    ) {
    }

    /**
     * @return list<BookDto>
     */
    public function __invoke(string $query): array
    {
        $books = $this->repository->searchByCriteria($query);

        return $this->mapper->mapList($books);
    }
}
