<?php 

namespace App\Http\Controllers\Books\Get;
use Illuminate\Http\Request;
use App\Service\BookService;

class GetBookIdHandler{
    public function __construct(private readonly BookService $bookService) {}

    public function __invoke(Request $request)
    {
        return $this->bookService->GetIdBook($request);
    }

}