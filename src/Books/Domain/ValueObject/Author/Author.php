<?php

declare(strict_types=1);

namespace App\Books\Domain\ValueObject\Author;

use App\Shared\Domain\ValueObject\Year;

final readonly class Author
{
    public function __construct(
        public(set) AuthorName $name,
        public(set) ?Year $birthYear = null,
        public(set) ?Year $deathYear = null,
    ) {
    }
}
