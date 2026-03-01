<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    /**
     * Store a newly created borrowing in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date',
        ]);

        $book = Book::findOrFail($request->book_id);

        // Check if book is available
        if ($book->available_quantity <= 0) {
            return redirect()->back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
        }

        // Check if user already borrowed this book
        $existingBorrow = Borrowing::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->whereNull('return_date')
            ->first();

        if ($existingBorrow) {
            return redirect()->back()->with('error', 'Anda sudah meminjam buku ini. Kembalikan terlebih dahulu sebelum meminjam lagi.');
        }

        // Create borrowing record
        Borrowing::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'borrow_date' => $request->borrow_date,
            'due_date' => $request->due_date,
            'return_date' => null,
            'status' => 'active',
        ]);

        // Update book available quantity
        $book->decrement('available_quantity');

        return redirect()->route('dashboard')->with('success', 'Buku berhasil dipinjam! Durasi peminjaman: ' . $request->due_date);
    }

    /**
     * Return a borrowed book
     */
    public function return(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke peminjaman ini.');
        }

        // Instead of immediately finalizing the return, create a return request
        // so admin can confirm receipt. This prevents double-processing and
        // allows admin to inspect condition before finalizing.
        $borrowing->update([
            'status' => 'return_requested',
            'return_requested_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Permintaan pengembalian telah dikirim. Menunggu konfirmasi admin.');
    }
}
