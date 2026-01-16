<?php

declare(strict_types=1);

namespace App\Books\Infrastructure\Controller;

use App\Books\Application\Service\SearchBooks;
use App\Books\Domain\Entity\Book;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class SearchBooksController extends AbstractController
{
    public function __construct(
        private readonly SearchBooks $searchQuery,
    ) {
    }

    #[Route('/api/books', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the list of books',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: new Model(type: Book::class)
            )
        )
    )]
    #[OA\Parameter(name: 'search', description: 'The search term', in: 'query', schema: new OA\Schema(type: 'string'))]
    public function search(Request $request): JsonResponse
    {
        $query = (string) $request->query->get('search', '');
        $books = ($this->searchQuery)($query);

        return $this->json($books);
    }
}
