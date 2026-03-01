<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('author', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('isbn', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Apply category filter - hanya jika category_id tidak kosong
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', (int)$request->category);
        }

        $books = $query->get();
        $categories = \App\Models\Category::all();
        return view('admin.books.index', compact('books', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:255|unique:books',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['available_quantity'] = $request->quantity;

        if ($request->hasFile('image')) {
            // Ensure directory exists
            $booksDir = public_path('images/books');
            if (!is_dir($booksDir)) {
                mkdir($booksDir, 0755, true);
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move($booksDir, $imageName);
            $data['image'] = $imageName;
        }

        Book::create($data);

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('admin.books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('admin.books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:255|unique:books,isbn,' . $book->id,
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['available_quantity'] = $request->quantity;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($book->image && file_exists(public_path('images/books/' . $book->image))) {
                unlink(public_path('images/books/' . $book->image));
            }

            // Ensure directory exists
            $booksDir = public_path('images/books');
            if (!is_dir($booksDir)) {
                mkdir($booksDir, 0755, true);
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move($booksDir, $imageName);
            $data['image'] = $imageName;
        }

        $book->update($data);

        return redirect()->route('admin.books.show', $book)->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Toggle the featured status of the specified book.
     */
    public function toggleFeatured(Book $book)
    {
        $book->update(['featured' => !$book->featured]);

        $message = $book->featured ? 'Buku berhasil ditambahkan ke unggulan.' : 'Buku berhasil dihapus dari unggulan.';

        return redirect()->route('admin.books.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // Delete image if exists
        if ($book->image && file_exists(public_path('images/books/' . $book->image))) {
            unlink(public_path('images/books/' . $book->image));
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil dihapus.');
    }
}
