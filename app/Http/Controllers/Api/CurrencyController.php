<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CurrencyRate;
use App\Services\CurrencyConverterService;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    public function __construct(
        private readonly CurrencyConverterService $currencyConverter
    ) {}

    public function getRate(): JsonResponse
    {
        $rate = CurrencyRate::where('from_currency', 'HUF')
            ->where('to_currency', 'EUR')
            ->first();

        if ($rate === null) {
            return response()->json([
                'success' => false,
                'message' => 'Nincs elérhető árfolyam adat.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'from' => $rate->from_currency,
                'to' => $rate->to_currency,
                'rate' => (float) $rate->rate,
                'last_updated' => $rate->updated_at->toIso8601String(),
                'age_hours' => $rate->getAgeInHours(),
                'is_stale' => $rate->isStale(24),
                'is_manual' => $rate->is_manual,
            ],
        ]);
    }

    public function convertAmount(float $amount): JsonResponse
    {
        $result = $this->currencyConverter->convertHufToEur($amount);

        return response()->json([
            'success' => true,
            'data' => [
                'amount_huf' => $amount,
                'amount_eur' => $result,
                'rate_age_hours' => $this->currencyConverter->getRateAge(),
            ],
        ]);
    }
}
