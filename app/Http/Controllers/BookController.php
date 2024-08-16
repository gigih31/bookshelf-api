<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::paginate(10); 

        return response()->json($books);
    }

    public function show($book_id)
    {
        
        $book = Book::find($book_id);

        if (!$book) {
            return response()->json([
                'message' => "No query results for model [App\\Models\\Book] {$book_id}"
            ], 404);
        }

      
        if ($book->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'This action is unauthorized.'
            ], 403);
        }

        return response()->json($book, 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|string|unique:books',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published' => 'required|date',
            'publisher' => 'required|string|max:255',
            'pages' => 'required|integer|min:1',
            'description' => 'required|string',
            'website' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $book = Book::create([
            'isbn' => $request->isbn,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'author' => $request->author,
            'published' => $request->published,
            'publisher' => $request->publisher,
            'pages' => $request->pages,
            'description' => $request->description,
            'website' => $request->website,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Book created',
            'book' => $book
        ], 201);
    }

    public function update(Request $request, $book_id)
    {

        $book = Book::find($book_id);

        if (!$book) {
            return response()->json([
                'message' => "No query results for model [App\\Models\\Book] {$book_id}"
            ], 404);
        }

        if ($book->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'This action is unauthorized.'
            ], 403);
        }

       
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|string|unique:books,isbn,' . $book_id,
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'published' => 'nullable|date',
            'publisher' => 'nullable|string|max:255',
            'pages' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $book->update($validator->validated());

        return response()->json([
            'message' => 'Book updated',
            'book' => $book
        ], 200);
    }
    public function destroy($book_id)
    {
    
        $book = Book::find($book_id);

        if (!$book) {
            return response()->json([
                'message' => "No query results for model [App\\Models\\Book] {$book_id}"
            ], 404);
        }

       
        if ($book->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'This action is unauthorized.'
            ], 403);
        }

        $bookDetails = $book->toArray();

       
        $book->delete();

        return response()->json([
            'message' => 'Book deleted',
            'book' => $bookDetails
        ], 200);
    }
}
