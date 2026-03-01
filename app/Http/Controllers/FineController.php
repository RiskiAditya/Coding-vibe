<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FineController extends Controller
{
    /**
     * Display member's fines
     */
    public function myFines()
    {
        $fines = Fine::whereHas('borrowing', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('borrowing.book')->get();

        $totalPending = $fines->where('status', 'pending')->sum('amount');
        $totalPaid = $fines->where('status', 'paid')->sum('amount');

        return view('fines.my-fines', compact('fines', 'totalPending', 'totalPaid'));
    }

    /**
     * Pay a fine (set status to waiting_confirmation)
     */
    public function pay(Fine $fine)
    {
        // Verify that the fine belongs to the current user
        if ($fine->borrowing->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke denda ini.');
        }

        // Set status to waiting_confirmation instead of immediately marking as paid
        $fine->update([
            'status' => 'waiting_confirmation',
        ]);

        return redirect()->route('fines.my-fines')->with('success', 'Permintaan pembayaran telah dikirim. Menunggu konfirmasi admin.');
    }

    /**
     * Display fines list for admin
     */
    public function adminFines()
    {
        $fines = Fine::with(['borrowing.user', 'borrowing.book'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPending = $fines->where('status', 'pending')->sum('amount');
        $totalPaid = $fines->where('status', 'paid')->sum('amount');
        $overdue = $fines->filter(function ($fine) {
            return $fine->isOverdue();
        })->count();

        return view('admin.fines.index', compact('fines', 'totalPending', 'totalPaid', 'overdue'));
    }

    /**
     * Mark fine as paid (admin)
     */
    public function markAsPaid(Fine $fine)
    {
        $fine->markAsPaid();
        return redirect()->back()->with('success', 'Denda berhasil ditandai sebagai dibayar.');
    }

    /**
     * Delete a fine (admin)
     */
    public function destroy(Fine $fine)
    {
        $fine->delete();
        return redirect()->back()->with('success', 'Denda berhasil dihapus.');
    }
}
