<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'query' => ['required', 'string', 'min:2', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'query.required' => 'A keresési kifejezés kötelező.',
            'query.min' => 'A keresési kifejezés minimum 2 karakter legyen.',
            'query.max' => 'A keresési kifejezés maximum 255 karakter lehet.',
        ];
    }
}
