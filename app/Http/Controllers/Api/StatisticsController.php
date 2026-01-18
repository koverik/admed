<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
    public function __construct(
        private readonly BookService $bookService
    ) {}

    public function expensiveBooks(): JsonResponse
    {
        $books = $this->bookService->getExpensiveBooks();

        return response()->json([
            'success' => true,
            'message' => 'Átlagár feletti könyvek',
            'data' => $books,
            'count' => $books->count(),
        ]);
    }

    public function popularCategories(): JsonResponse
    {
        $categories = $this->bookService->getPopularCategories();

        return response()->json([
            'success' => true,
            'message' => 'Top 3 legnépszerűbb kategória',
            'data' => $categories,
        ]);
    }

    public function topFantasyAndSciFi(): JsonResponse
    {
        $books = $this->bookService->getTopFantasyAndSciFiBooks();

        return response()->json([
            'success' => true,
            'message' => 'Top 3 legdrágább Fantasy és Sci-Fi könyvek',
            'data' => $books,
        ]);
    }
}
