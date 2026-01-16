<?php

declare(strict_types=1);

namespace App\Books\Domain\Entity;

use App\Books\Domain\ValueObject\Author\Author;
use App\Books\Domain\ValueObject\Book\BookId;
use App\Books\Domain\ValueObject\Book\BookTitle;
use App\Books\Domain\ValueObject\Book\Subject;

final readonly class Book
{
    /**
     * @param list<Subject> $subjects
     * @param list<Author>  $authors
     */
    public function __construct(
        public(set) BookId $id,
        public(set) BookTitle $title,
        public(set) array $subjects,
        public(set) array $authors,
    ) {
    }
}
