<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CurrencyRate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyConverterService
{
    private const CACHE_KEY = 'huf_to_eur_rate';

    private const CACHE_TTL = 3600;

    private const STALE_THRESHOLD_HOURS = 24;

    private const HARDCODED_FALLBACK = 0.0025;

    public function __construct(
        private readonly string $apiUrl = 'https://api.exchangerate-api.com/v4/latest/HUF'
    ) {}

    public function convertHufToEur(float $amountInHuf): float
    {
        $rate = $this->getExchangeRate();

        if ($rate === null) {
            return 0.0;
        }

        return round($amountInHuf * $rate, 2);
    }

    private function getExchangeRate(): ?float
    {
        try {
            return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
                
                $rate = $this->fetchFromApi();

                if ($rate !== null) {

                    $this->saveRateToDatabase($rate);

                    return $rate;
                }

                return $this->getFallbackFromDatabase();
            });
        } catch (\Exception $e) {
            Log::error('Currency conversion error', [
                'message' => $e->getMessage(),
            ]);

            return $this->getFallbackFromDatabase();
        }
    }

    private function fetchFromApi(): ?float
    {
        try {
            $response = Http::timeout(5)->get($this->apiUrl);

            if (! $response->successful()) {
                Log::warning('Failed to fetch exchange rate from API', [
                    'status' => $response->status(),
                ]);

                return null;
            }

            $data = $response->json();
            $rate = $data['rates']['EUR'] ?? null;

            if ($rate === null) {
                Log::warning('EUR rate not found in API response');

                return null;
            }

            Log::info('Successfully fetched exchange rate from API', [
                'rate' => $rate,
            ]);

            return (float) $rate;
        } catch (\Exception $e) {
            Log::error('API fetch error', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function saveRateToDatabase(float $rate): void
    {
        try {
            CurrencyRate::updateRate('HUF', 'EUR', $rate);

            Log::info('Exchange rate saved to database', [
                'rate' => $rate,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save rate to database', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function getFallbackFromDatabase(): float
    {
        try {
            $record = CurrencyRate::where('from_currency', 'HUF')
                ->where('to_currency', 'EUR')
                ->first();

            if ($record) {
                $ageInHours = $record->getAgeInHours();

                if ($record->isStale(self::STALE_THRESHOLD_HOURS)) {
                    Log::warning('Using stale database fallback rate', [
                        'rate' => $record->rate,
                        'age_hours' => $ageInHours,
                    ]);
                } else {
                    Log::info('Using database fallback rate', [
                        'rate' => $record->rate,
                        'age_hours' => $ageInHours,
                    ]);
                }

                return (float) $record->rate;
            }

            Log::warning('Database fallback not available, using hardcoded rate', [
                'rate' => self::HARDCODED_FALLBACK,
            ]);

            return self::HARDCODED_FALLBACK;
        } catch (\Exception $e) {
            Log::error('Database fallback error', [
                'message' => $e->getMessage(),
            ]);

            return self::HARDCODED_FALLBACK;
        }
    }

    public function getLastUpdate(): ?string
    {
        $record = CurrencyRate::where('from_currency', 'HUF')
            ->where('to_currency', 'EUR')
            ->first();

        return $record ? $record->updated_at->toIso8601String() : null;
    }

    public function getRateAge(): ?int
    {
        $record = CurrencyRate::where('from_currency', 'HUF')
            ->where('to_currency', 'EUR')
            ->first();

        return $record ? $record->getAgeInHours() : null;
    }
}
