<?php

declare(strict_types=1);

namespace App\Books\Domain\Exception;

use App\Books\Domain\ValueObject\Book\BookId;
use App\Shared\Domain\DomainError;

class BookNotFoundException extends DomainError
{
    public function __construct(private readonly BookId $bookId)
    {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'BOOK_NOT_FOUND';
    }

    protected function errorMessage(): string
    {
        return sprintf('The book with ID <%s> was not found.', $this->bookId->value);
    }
}
