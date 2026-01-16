<?php

declare(strict_types=1);

namespace App\Books\Infrastructure\Controller;

use App\Books\Application\Service\GetBook;
use App\Books\Domain\Entity\Book;
use App\Books\Domain\Exception\BookNotFoundException;
use App\Books\Domain\ValueObject\Book\BookId;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetBookController extends AbstractController
{
    public function __construct(
        private readonly GetBook $findByIdQuery,
    ) {
    }

    #[Route('/api/books/{id}', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the book details',
        content: new OA\JsonContent(ref: new Model(type: Book::class))
    )]
    #[OA\Response(
        response: 404,
        description: 'Book not found'
    )]
    public function find(int $id): JsonResponse
    {
        try {
            $book = ($this->findByIdQuery)(new BookId($id));
        } catch (BookNotFoundException) {
            return $this->json(['error' => 'Book not found'], 404);
        }

        return $this->json($book);
    }
}
