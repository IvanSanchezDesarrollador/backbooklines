<?php 

namespace App\Http\Controllers\Store\Get;

use App\Service\StoreService;
use Illuminate\Http\Request;

class GetStoreBooksHandler{

    public function __construct(private readonly StoreService $bookService) {}

    public function __invoke(Request $request)
    {
        return $this->bookService->GetBooks($request);
    }
}