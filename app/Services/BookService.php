<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BookService
{
    public function __construct(
        private readonly CurrencyConverterService $currencyConverter
    ) {}

    public function getAllBooks(): Collection
    {
        return Book::with(['author', 'category'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getBookById(int $id): ?Book
    {
        return Book::with(['author', 'category'])->find($id);
    }

    public function createBook(array $data): Book
    {
        return Book::create($data);
    }

    public function searchBooks(string $query): Collection
    {
        return Book::with(['author', 'category'])
            ->search($query)
            ->get();
    }

    public function getExpensiveBooks(): Collection
    {
        return Book::with(['author', 'category'])
            ->aboveAveragePrice()
            ->orderBy('price_huf', 'desc')
            ->get();
    }

    public function getPopularCategories(): array
    {
        return DB::table('categories')
            ->join('books', 'categories.id', '=', 'books.category_id')
            ->select(
                'categories.name',
                DB::raw('COUNT(books.id) as book_count'),
                DB::raw('AVG(books.price_huf) as average_price')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('book_count', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'book_count' => (int) $category->book_count,
                    'average_price_huf' => round((float) $category->average_price, 2),
                    'average_price_eur' => $this->currencyConverter
                        ->convertHufToEur((float) $category->average_price),
                ];
            })
            ->toArray();
    }

    public function getTopFantasyAndSciFiBooks(): array
    {
        $books = Book::with(['author', 'category'])
            ->whereHas('category', function ($query) {
                $query->whereIn('name', ['Fantasy', 'Science Fiction', 'Sci-Fi']);
            })
            ->orderBy('price_huf', 'desc')
            ->get()
            ->groupBy('category.name')
            ->map(function ($categoryBooks) {
                return $categoryBooks->take(3);
            });

        return [
            'fantasy' => $books->get('Fantasy', collect())->values(),
            'sci_fi' => $books->get('Science Fiction', $books->get('Sci-Fi', collect()))->values(),
        ];
    }

    public function addPriceInEur(Book $book): array
    {
        $bookArray = $book->toArray();
        $bookArray['price_eur'] = $this->currencyConverter
            ->convertHufToEur((float) $book->price_huf);

        return $bookArray;
    }
}
