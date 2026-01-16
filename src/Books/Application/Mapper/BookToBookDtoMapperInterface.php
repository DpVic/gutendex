<?php

declare(strict_types=1);

namespace App\Books\Application\Mapper;

use App\Books\Application\Dto\BookDto;
use App\Books\Domain\Entity\Book;

interface BookToBookDtoMapperInterface
{
    public function map(Book $book): BookDto;

    /**
     * @param list<Book> $books
     *
     * @return list<BookDto>
     */
    public function mapList(array $books): array;
}
