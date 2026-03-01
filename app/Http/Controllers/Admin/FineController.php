<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fine;
use Illuminate\Http\Request;

class FineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fines = Fine::with(['borrowing.user', 'borrowing.book'])->get();
        return view('admin.fines.index', compact('fines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.fines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string',
            'status' => 'required|in:pending,waiting_confirmation,paid',
        ]);

        Fine::create($request->all());

        return redirect()->route('admin.fines.index')->with('success', 'Denda berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fine $fine)
    {
        return view('admin.fines.show', compact('fine'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fine $fine)
    {
        return view('admin.fines.edit', compact('fine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fine $fine)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string',
            'status' => 'required|in:pending,waiting_confirmation,paid',
        ]);

        $fine->update($request->only(['amount', 'reason', 'status']));

        return redirect()->route('admin.fines.index')->with('success', 'Denda berhasil diperbarui.');
    }

    /**
     * Mark the fine as paid.
     */
    public function markAsPaid(Fine $fine)
    {
        $fine->markAsPaid();

        return redirect()->route('admin.fines.index')->with('success', 'Denda berhasil ditandai sebagai lunas.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fine $fine)
    {
        $fine->delete();

        return redirect()->route('admin.fines.index')->with('success', 'Denda berhasil dihapus.');
    }
}
