<?php

declare(strict_types=1);

namespace App\Books\Domain\ValueObject\Book;

use App\Shared\Domain\ValueObject\StringValueObject;
use Webmozart\Assert\Assert;

final readonly class BookTitle extends StringValueObject
{
    public function __construct(string $value)
    {
        Assert::notEmpty($value, 'Book title cannot be empty');
        Assert::notWhitespaceOnly($value, 'Book title cannot be whitespace only');
        parent::__construct($value);
    }
}
