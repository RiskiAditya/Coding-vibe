<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Admin return a borrowed book (no user_id check)
     */
    public function adminReturn(Borrowing $borrowing, Request $request)
    {
        // Get condition from request, default to 'good'
        $condition = $request->input('condition', 'good');
        
        // Validate condition
        if (!in_array($condition, ['good', 'late', 'damaged', 'lost'])) {
            $condition = 'good';
        }

        // Finalize return (process stock and fines). Clear any return request timestamp.
        $borrowing->returnBook($condition);
        $borrowing->update(['return_requested_at' => null]);

        return redirect()->back()->with('success', 'Buku berhasil dikonfirmasi dikembalikan oleh admin.');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'book']);

        // Search by user name or book title
        if ($request->has('search') && !empty($request->search)) {
            $term = $request->search;
            $query->where(function($q) use ($term) {
                $q->whereHas('user', function($q2) use ($term) {
                    $q2->where('name', 'LIKE', "%{$term}%");
                })->orWhereHas('book', function($q3) use ($term) {
                    $q3->where('title', 'LIKE', "%{$term}%");
                });
            });
        }

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['active','returned','overdue'])) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderBy('borrow_date', 'desc')->paginate(12)->withQueryString();

        return view('admin.borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.borrowings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date',
        ]);

        Borrowing::create($request->all());

        return redirect()->route('admin.borrowings.index')->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        return view('admin.borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowing $borrowing)
    {
        return view('admin.borrowings.edit', compact('borrowing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'return_date' => 'nullable|date|after_or_equal:borrow_date',
            'status' => 'required|in:active,returned,overdue',
            'condition' => 'nullable|in:good,late,damaged,lost',
        ]);

        $condition = $request->input('condition', 'good');

        if ($request->has('return_date') && $request->return_date) {
            $borrowing->returnBook($condition);
        } else {
            $borrowing->update($request->only(['status']));
        }

        return redirect()->route('admin.borrowings.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        $borrowing->delete();

        return redirect()->route('admin.borrowings.index')->with('success', 'Peminjaman berhasil dihapus.');
    }
}
