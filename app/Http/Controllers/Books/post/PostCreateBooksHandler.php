<?php

namespace App\Http\Controllers\Books\Post;

use App\Http\Requests\Books\PostBooksRequest;
use App\Service\BookService;

class PostCreateBooksHandler
{

    public function __construct(private readonly BookService $bookService) {}

    public function __invoke(PostBooksRequest $request)
    {
        return $this->bookService->postCrearBooks($request);
    }
}
