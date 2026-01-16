<?php

declare(strict_types=1);

namespace App\Books\Domain\ValueObject\Author;

use App\Shared\Domain\ValueObject\StringValueObject;
use Webmozart\Assert\Assert;

final readonly class AuthorName extends StringValueObject
{
    public function __construct(string $value)
    {
        Assert::notEmpty($value, 'Author name cannot be empty');
        Assert::notWhitespaceOnly($value, 'Author name cannot be whitespace only');
        parent::__construct($value);
    }
}
