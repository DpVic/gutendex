<?php

declare(strict_types=1);

namespace App\Books\Application\Dto;

/**
 * @phpstan-type AuthorShape array{
 *    name: string,
 *    birth_year?: int|null,
 *    death_year?: int|null
 *  }
 */
final readonly class AuthorDto implements \JsonSerializable
{
    public function __construct(
        public string $name,
        public ?int $birthYear,
        public ?int $deathYear,
    ) {
    }

    /**
     * @return AuthorShape
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'birth_year' => $this->birthYear,
            'death_year' => $this->deathYear,
        ];
    }
}
