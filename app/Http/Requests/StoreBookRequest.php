<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'author_id' => ['required', 'integer', 'exists:authors,id'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'release_date' => ['required', 'date', 'date_format:Y-m-d'],
            'price_huf' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'A könyv címe kötelező.',
            'title.max' => 'A könyv címe maximum 255 karakter lehet.',
            'author_id.required' => 'A szerző megadása kötelező.',
            'author_id.exists' => 'A megadott szerző nem létezik.',
            'category_id.required' => 'A kategória megadása kötelező.',
            'category_id.exists' => 'A megadott kategória nem létezik.',
            'release_date.required' => 'A megjelenés dátuma kötelező.',
            'release_date.date' => 'A megjelenés dátuma érvénytelen formátumú.',
            'release_date.date_format' => 'A megjelenés dátuma formátuma: Y-m-d (pl. 2024-01-15).',
            'price_huf.required' => 'Az ár megadása kötelező.',
            'price_huf.numeric' => 'Az árnak számnak kell lennie.',
            'price_huf.min' => 'Az ár nem lehet negatív.',
            'price_huf.max' => 'Az ár túl nagy.',
        ];
    }
}
