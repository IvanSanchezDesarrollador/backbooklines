<?php

namespace App\Http\Requests\Books;

use Illuminate\Foundation\Http\FormRequest;

class PostBooksRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'discountPercentage' => ['required', 'numeric', 'regex:/^\d+(\.\d{1})?$/'],
            'rating' => ['required', 'numeric', 'regex:/^\d+(\.\d{1})?$/'],
            'stock' => ['required', 'integer'],
            'author' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'thumbnail' => ['required', 'array'],
            'thumbnail.*' => ['file', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Aquí está la corrección
            'images' => ['required', 'array'],
            'images.*' => ['file', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Aquí está la corrección
        ];
    }
}
