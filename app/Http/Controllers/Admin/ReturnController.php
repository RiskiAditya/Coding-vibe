<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookReturn;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    /**
     * Display a listing of returned books.
     */
    public function index()
    {
        $returns = BookReturn::with(['borrowing.user', 'borrowing.book', 'staff'])
            ->orderBy('return_date', 'desc')
            ->get();

        return view('admin.returns.index', compact('returns'));
    }

    /**
     * Show the form for creating a new return.
     */
    public function create()
    {
        $borrowings = Borrowing::where('status', '!=', 'returned')
            ->with(['user', 'book'])
            ->get();

        return view('admin.returns.create', compact('borrowings'));
    }

    /**
     * Store a newly created return in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
            'book_condition' => 'required|in:good,damaged,lost',
            'notes' => 'nullable|string',
        ]);

        $borrowing = Borrowing::findOrFail($request->borrowing_id);

        BookReturn::create([
            'borrowing_id' => $request->borrowing_id,
            'staff_id' => auth()->id(),
            'book_condition' => $request->book_condition,
            'notes' => $request->notes,
        ]);

        // Update borrowing status
        $borrowing->update(['status' => 'returned', 'return_date' => now()]);

        // Handle fines based on condition
        if ($request->book_condition === 'damaged') {
            $borrowing->createDamageFine(25000);
        } elseif ($request->book_condition === 'lost') {
            $borrowing->createLostFine();
        } elseif ($borrowing->return_date > $borrowing->due_date) {
            // Create late fine
            $borrowing->createLateFine();
        }

        return redirect()->route('admin.returns.index')
            ->with('success', 'Pengembalian buku berhasil dicatat.');
    }

    /**
     * Display the specified return.
     */
    public function show(BookReturn $return)
    {
        return view('admin.returns.show', compact('return'));
    }

    /**
     * Remove the specified return from storage.
     */
    public function destroy(BookReturn $return)
    {
        $borrowing = $return->borrowing;
        $return->delete();

        // Reset borrowing status
        $borrowing->update(['status' => 'active', 'return_date' => null]);

        return redirect()->route('admin.returns.index')
            ->with('success', 'Record pengembalian berhasil dihapus.');
    }
}
