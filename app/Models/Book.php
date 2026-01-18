<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author_id',
        'category_id',
        'release_date',
        'price_huf',
    ];

    protected $casts = [
        'release_date' => 'date',
        'price_huf' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeSearch(Builder $query, string $searchTerm): Builder
    {
        return $query->where(function (Builder $q) use ($searchTerm) {
            $q->where('title', 'LIKE', "%{$searchTerm}%")
                ->orWhereHas('author', function (Builder $authorQuery) use ($searchTerm) {
                    $authorQuery->where('name', 'LIKE', "%{$searchTerm}%");
                })
                ->orWhereHas('category', function (Builder $categoryQuery) use ($searchTerm) {
                    $categoryQuery->where('name', 'LIKE', "%{$searchTerm}%");
                });
        });
    }

    public function scopeAboveAveragePrice(Builder $query): Builder
    {
        $averagePrice = static::query()->avg('price_huf');

        return $query->where('price_huf', '>', $averagePrice);
    }
}
