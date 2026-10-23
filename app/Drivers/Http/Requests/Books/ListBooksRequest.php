<?php

namespace App\Drivers\Http\Requests\Books;

use Illuminate\Foundation\Http\FormRequest;

class ListBooksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function perPage(): int
    {
        return (int) ($this->validated('per_page') ?? 15);
    }

    public function page(): int
    {
        return (int) ($this->validated('page') ?? 1);
    }
}
