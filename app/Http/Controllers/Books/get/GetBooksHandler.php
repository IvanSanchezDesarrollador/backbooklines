<?php

namespace App\Http\Controllers\Books\Get;
use Illuminate\Http\Request;

use App\Service\BookService;

class GetBooksHandler
{
    public function __construct(private readonly BookService $bookService) {}

    public function __invoke(Request $request)
    {
        return $this->bookService->GetBooks($request);
    }
}
