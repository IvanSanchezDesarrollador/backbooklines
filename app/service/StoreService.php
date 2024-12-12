<?php

namespace App\Service;

use App\Models\Book;
use Illuminate\Http\Request;

class StoreService
{

    public function GetBooks(Request $request)
    {
        try {
            $bookQuery  = Book::with(['imagenes', 'thumbnail'])
                ->orderBy('created_at', 'desc');

            if ($request->has('search')) {
                $search = $request->query('search');
                /**whereRaw acepta consultas mas comlejas que laravel aun no puede procesar */
                $bookQuery->whereRaw('title LIKE ?', ["%{$search}%"]);
            }

            $book = $bookQuery->paginate(10);

            $bookTransformados = $book->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'price' => $book->price,
                    'discountPercentage' => $book->discountPercentage,
                    'rating' => $book->rating,
                    'author' => $book->author,
                    'category' => $book->category,
                    'thumbnail' => $book->thumbnail->map(function ($imagen) {
                        return [
                            'id' => $imagen->id,
                            'type' => $imagen->type,
                            'url' => $imagen->url,
                        ];
                    }),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $bookTransformados,
                'pagination' => [
                    'current_page' => $book->currentPage(),
                    'last_page' => $book->lastPage(),
                    'per_page' => $book->perPage(),
                    'total' => $book->total(),
                    'links' => [
                        'first' => $book->url(1),
                        'last' => $book->url($book->lastPage()),
                        'prev' => $book->previousPageUrl(),
                        'next' => $book->nextPageUrl(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los libros',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
