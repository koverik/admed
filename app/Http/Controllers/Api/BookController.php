<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BookController extends Controller
{
    public function __construct(
        private readonly BookService $bookService
    ) {}

    public function index(): JsonResponse
    {
        $books = $this->bookService->getAllBooks();

        return response()->json([
            'success' => true,
            'data' => $books,
        ]);
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = $this->bookService->createBook($request->validated());
        $book->load(['author', 'category']);

        return response()->json([
            'success' => true,
            'message' => 'Könyv sikeresen létrehozva.',
            'data' => $book,
        ], Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $book = $this->bookService->getBookById($id);

        if ($book === null) {
            return response()->json([
                'success' => false,
                'message' => 'Könyv nem található.',
            ], Response::HTTP_NOT_FOUND);
        }

        $bookWithEur = $this->bookService->addPriceInEur($book);

        return response()->json([
            'success' => true,
            'data' => $bookWithEur,
        ]);
    }

    public function search(SearchBookRequest $request): JsonResponse
    {
        $query = $request->validated()['query'];
        $books = $this->bookService->searchBooks($query);

        return response()->json([
            'success' => true,
            'data' => $books,
            'count' => $books->count(),
        ]);
    }
}
