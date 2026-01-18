<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            ['name' => 'J.R.R. Tolkien'],
            ['name' => 'George R.R. Martin'],
            ['name' => 'Isaac Asimov'],
            ['name' => 'Frank Herbert'],
            ['name' => 'Ursula K. Le Guin'],
            ['name' => 'Brandon Sanderson'],
            ['name' => 'Philip K. Dick'],
            ['name' => 'Douglas Adams'],
            ['name' => 'Terry Pratchett'],
            ['name' => 'Neil Gaiman'],
        ];

        foreach ($authors as $author) {
            Author::create($author);
        }
    }
}
