<?php

namespace App\Service;

use App\Http\Requests\Books\PostBooksRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookService
{

    public function postCrearBooks(PostBooksRequest $postBooksRequest)
    {

        try {
            $existBook = Book::where('title', $postBooksRequest->input('title'))->first();

            if ($existBook) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe un libro con este nombre',
                ], 409); // 409 Conflict
            }

            $book = Book::create([
                'title' => $postBooksRequest->input('title'),
                'description' => $postBooksRequest->input('description'),
                'price' => (float)$postBooksRequest->input('price'),
                'discountPercentage' => (float) $postBooksRequest->input('discountPercentage'),
                'rating' => (float) $postBooksRequest->input('rating'),
                'stock' => (int)$postBooksRequest->input('stock'),
                'author' =>  $postBooksRequest->input('author'),
                'category' => $postBooksRequest->input('category'),
            ]);

            $numAleario = Str::random(10);

            $typeThumbnail = "thumbnail";

            foreach ($postBooksRequest->file('thumbnail') as $index => $imageFile) {
                $fileExtension = $imageFile->getClientOriginalExtension();
                $uniqueFileName = $numAleario . '_thumbnail' . $index . '.' . $fileExtension;
                $path = $imageFile->storeAs('thumbnail', $uniqueFileName, 'books');

                $book->thumbnail()->create([
                    'url' => $path,
                    'type' => $typeThumbnail,
                    'imageable_id' => $book->id,
                    'imageable_type' => Book::class,
                ]);
            }

            $typeImage = "image";


            foreach ($postBooksRequest->file('images') as $index => $imageFile) {
                $fileExtension = $imageFile->getClientOriginalExtension();
                $uniqueFileName = $numAleario . '_image' . $index . '.' . $fileExtension;
                $path = $imageFile->storeAs('images', $uniqueFileName, 'books');

                $book->imagenes()->create([
                    'url' => $path,
                    'type' => $typeImage,
                    'imageable_id' => $book->id,
                    'imageable_type' => Book::class,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Libro creado correctamente',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error del servidor al crear el libro.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


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
                    'description' => $book->description,
                    'price' => $book->price,
                    'discountPercentage' => $book->discountPercentage,
                    'rating' => $book->rating,
                    'stock' => $book->stock,
                    'author' => $book->author,
                    'category' => $book->category,
                    'updated_at' => $book->updated_at,
                    'imagenes' => $book->imagenes->map(function ($imagen) {
                        return [
                            'id' => $imagen->id,
                            'type' => $imagen->type,
                            'url' => $imagen->url,
                        ];
                    }),
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

    public function GetIdBook(Request $request)
    {
        try {
            $request->validate([
                'id' => ['required', 'exists:books,id'],
            ]);

            $idBooks = $request->query('id');

            $book = Book::with(['imagenes', 'thumbnail'])
                ->find($idBooks);

            if (!$book) {
                return response()->json([
                    'success' => false,
                    'message' => 'El libro no existe',
                ], 409);
            }


            $bookTransformados = [
                'id' => $book->id,
                'title' => $book->title,
                'description' => $book->description,
                'price' => $book->price,
                'discountPercentage' => $book->discountPercentage,
                'rating' => $book->rating,
                'stock' => $book->stock,
                'author' => $book->author,
                'category' => $book->category,
                'updated_at' => $book->updated_at,
                'imagenes' => $book->imagenes->map(function ($imagen) {
                    return [
                        'id' => $imagen->id,
                        'type' => $imagen->type,
                        'url' => $imagen->url,
                    ];
                }),
                'thumbnail' => $book->thumbnail->map(function ($imagen) {
                    return [
                        'id' => $imagen->id,
                        'type' => $imagen->type,
                        'url' => $imagen->url,
                    ];
                }),
            ];

            return response()->json([
                'success' => true,
                'data' => $bookTransformados,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el libro',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
