<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    protected $fillable = [
        'from_currency',
        'to_currency',
        'rate',
        'is_manual',
    ];

    protected $casts = [
        'rate' => 'decimal:6',
        'is_manual' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function getLatestRate(string $from, string $to): ?float
    {
        $record = static::where('from_currency', $from)
            ->where('to_currency', $to)
            ->first();

        return $record ? (float) $record->rate : null;
    }

    public static function updateRate(string $from, string $to, float $rate): void
    {
        static::updateOrCreate(
            [
                'from_currency' => $from,
                'to_currency' => $to,
            ],
            [
                'rate' => $rate,
                'is_manual' => false,
            ]
        );
    }

    public function getAgeInHours(): int
    {
        return (int) now()->diffInHours($this->updated_at);
    }

    public function isStale(int $maxHours = 24): bool
    {
        return $this->getAgeInHours() > $maxHours;
    }
}
