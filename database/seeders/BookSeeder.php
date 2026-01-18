<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            // Fantasy books
            [
                'title' => 'The Lord of the Rings',
                'author_id' => 1,
                'category_id' => 1,
                'release_date' => '1954-07-29',
                'price_huf' => 8900.00,
            ],
            [
                'title' => 'A Game of Thrones',
                'author_id' => 2,
                'category_id' => 1,
                'release_date' => '1996-08-06',
                'price_huf' => 6500.00,
            ],
            [
                'title' => 'The Way of Kings',
                'author_id' => 6,
                'category_id' => 1,
                'release_date' => '2010-08-31',
                'price_huf' => 7200.00,
            ],
            [
                'title' => 'American Gods',
                'author_id' => 10,
                'category_id' => 1,
                'release_date' => '2001-06-19',
                'price_huf' => 5800.00,
            ],
            [
                'title' => 'The Hobbit',
                'author_id' => 1,
                'category_id' => 1,
                'release_date' => '1937-09-21',
                'price_huf' => 4500.00,
            ],

            // Science Fiction books
            [
                'title' => 'Foundation',
                'author_id' => 3,
                'category_id' => 2,
                'release_date' => '1951-06-01',
                'price_huf' => 5200.00,
            ],
            [
                'title' => 'Dune',
                'author_id' => 4,
                'category_id' => 2,
                'release_date' => '1965-08-01',
                'price_huf' => 9500.00,
            ],
            [
                'title' => 'The Left Hand of Darkness',
                'author_id' => 5,
                'category_id' => 2,
                'release_date' => '1969-03-01',
                'price_huf' => 4800.00,
            ],
            [
                'title' => 'Do Androids Dream of Electric Sheep?',
                'author_id' => 7,
                'category_id' => 2,
                'release_date' => '1968-01-01',
                'price_huf' => 5500.00,
            ],
            [
                'title' => 'The Hitchhiker\'s Guide to the Galaxy',
                'author_id' => 8,
                'category_id' => 2,
                'release_date' => '1979-10-12',
                'price_huf' => 4200.00,
            ],
            [
                'title' => 'I, Robot',
                'author_id' => 3,
                'category_id' => 2,
                'release_date' => '1950-12-02',
                'price_huf' => 8700.00,
            ],

            // Other categories
            [
                'title' => 'Good Omens',
                'author_id' => 9,
                'category_id' => 3,
                'release_date' => '1990-05-01',
                'price_huf' => 5300.00,
            ],
            [
                'title' => 'The Color of Magic',
                'author_id' => 9,
                'category_id' => 1,
                'release_date' => '1983-11-24',
                'price_huf' => 4900.00,
            ],
            [
                'title' => 'A Clash of Kings',
                'author_id' => 2,
                'category_id' => 1,
                'release_date' => '1998-11-16',
                'price_huf' => 6700.00,
            ],
            [
                'title' => 'The Dispossessed',
                'author_id' => 5,
                'category_id' => 2,
                'release_date' => '1974-05-01',
                'price_huf' => 5100.00,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
