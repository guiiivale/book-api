<?php

namespace App\Drivers\Http\Requests\Books;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'regex:/^\d+$/', 'max:32'],
            'value' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'isbn.regex' => 'The ISBN must contain digits only.',
        ];
    }
}
