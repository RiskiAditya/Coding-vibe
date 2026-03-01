<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;

/**
 * HomeController
 * 
 * Controller yang bertanggung jawab untuk menangani halaman utama
 * dan navigasi dasar aplikasi Perpustakaan.
 * 
 * @author Library Management Team
 * @version 1.0
 */
class HomeController extends Controller
{
    /**
     * Display the welcome/home page
     * 
     * Menampilkan halaman selamat datang dengan informasi tentang
     * fitur-fitur utama aplikasi manajemen perpustakaan.
     *
     * @return View
     */
    public function index(): View
    {
        return view('welcome');
    }

    /**
     * Display the dashboard page
     *
     * Menampilkan halaman dashboard utama aplikasi setelah user login.
     * Dashboard menampilkan overview statistik dan informasi penting.
     *
     * @param Request $request
     * @return View
     */
    public function dashboard(Request $request): View
    {
        $total_books = Book::count();
        $categories = Category::all();

        // If admin, show global stats; otherwise show member-specific stats
        if (auth()->check() && auth()->user()->isAdmin()) {
            // For admin: count all active borrowings (not yet returned)
            $active_loans = \App\Models\Borrowing::where('status', 'active')->orWhereNull('return_date')->count();

            // Recent activities and loans for admin (latest 8)
            $current_loans = \App\Models\Borrowing::with(['user','book'])->where('status', 'active')->orderBy('borrow_date', 'desc')->limit(8)->get();

            // Pending return requests that require admin confirmation
            $pending_returns = \App\Models\Borrowing::where('status', 'return_requested')->count();

            // Pending fines across system
            $pending_fines = \App\Models\Fine::where('status', 'pending')->sum('amount');

            // Featured books for admin view
            $featured_books = Book::with('category')->get();

            // Prepare recent activities (simple): include pending return notification
            $recent_activities = [];
            if ($pending_returns > 0) {
                $recent_activities[] = [
                    'date' => now()->format('d M Y H:i'),
                    'type' => 'Return Request',
                    'detail' => "Ada {$pending_returns} pengembalian menunggu konfirmasi",
                ];
            }

            return view('dashboard', compact('featured_books', 'categories', 'total_books', 'active_loans', 'current_loans', 'pending_fines', 'recent_activities', 'pending_returns'));
        }

        // Member view
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

        $featured_books = $query->get();

        // Get current loans (active + return_requested, not yet fully returned)
        $current_loans = \App\Models\Borrowing::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->whereIn('status', ['active', 'return_requested'])
            ->with('book')
            ->orderBy('borrow_date', 'desc')
            ->get();
        $active_loans = $current_loans->count();
        
        // Get pending fines for member
        $pending_fines = \App\Models\Fine::whereHas('borrowing', function ($q) {
            $q->where('user_id', \Illuminate\Support\Facades\Auth::id());
        })->where('status', 'pending')->sum('amount');

        return view('dashboard', compact('featured_books', 'categories', 'total_books', 'active_loans', 'current_loans', 'pending_fines'));
    }

    /**
     * Display the books page
     *
     * Menampilkan halaman daftar buku dengan semua koleksi buku
     * yang tersedia di perpustakaan. Mendukung fitur pencarian.
     * Untuk guest user, tampilkan sample buku saja dengan pesan "Daftar untuk akses penuh"
     *
     * @param Request $request
     * @return View
     */
    public function books(Request $request): View
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

        if ($request->has('featured') && $request->featured == '1') {
            $query->where('featured', true);
        }

        if ($request->has('available') && $request->available == '1') {
            $query->where('available_quantity', '>', 0);
        }

        // Determine sample view:
        // - force sample if ?sample=1 is present
        // - or if user is not authenticated
        $isSampleView = ($request->has('sample') && (string)$request->sample === '1') || !auth()->check();

        if ($isSampleView) {
            // show only a few sample books to encourage registration/login
            $books = $query->limit(3)->get();
        } else {
            $books = $query->get();
        }

        $categories = Category::all();
        return view('buku', compact('books', 'categories', 'isSampleView'));
    }

    /**
     * Display book detail page
     *
     * Menampilkan halaman detail buku untuk dibaca oleh member
     *
     * @param Book $book
     * @return View
     */
    public function showBook(Book $book): View
    {
        $book->load('category');
        return view('book-detail', compact('book'));
    }

    /**
     * Display the book reader view
     *
     * Menampilkan halaman pembacaan buku digital dengan interface yang user-friendly.
     *
     * @param Book $book
     * @return View
     */
    public function readBook(Book $book): View
    {
        $book->load('category');
        return view('book-reader', compact('book'));
    }

    /**
     * Display member borrowing history
     *
     * Menampilkan riwayat peminjaman member yang sudah dikembalikan atau sedang dipinjam
     *
     * @return View
     */
    public function borrowingHistory(): View
    {
        $borrowings = \App\Models\Borrowing::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->with(['book', 'book.category'])
            ->orderBy('borrow_date', 'desc')
            ->paginate(15);
        
        return view('member-borrowing-history', compact('borrowings'));
    }

    /**
     * Get application statistics
     *
     * Mengambil statistik penting aplikasi untuk ditampilkan di dashboard.
     * Statistik mencakup jumlah buku, peminjaman aktif, dan denda.
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return [
            'total_books' => Book::count(),
            'active_loans' => 0,
            'pending_fines' => 0,
            'total_members' => 0,
        ];
    }

    /**
     * Display all books for member
     *
     * Menampilkan halaman lengkap semua buku untuk member dengan fitur baca dan pinjam.
     *
     * @param Request $request
     * @return View
     */
    public function allBooksMember(Request $request): View
    {
        $query = Book::with('category')->where('available_quantity', '>', 0);

        // Category filter - Check if category_id is provided and valid
        if ($request->has('category') && !empty($request->category)) {
            $categoryId = (int)$request->category;
            // Verify category exists before filtering
            if (Category::find($categoryId)) {
                $query->where('category_id', $categoryId);
            }
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('author', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('isbn', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $books = $query->paginate(12);
        $categories = Category::all();

        return view('all-books-member', compact('books', 'categories'));
    }

    /**
     * Display all books for admin
     *
     * Menampilkan halaman lengkap semua buku untuk admin dengan fitur manajemen.
     *
     * @param Request $request
     * @return View
     */
    public function allBooksAdmin(Request $request): View
    {
        $query = Book::with('category');

        // Category filter - Check if category_id is provided and valid
        if ($request->has('category') && !empty($request->category)) {
            $categoryId = (int)$request->category;
            // Verify category exists before filtering
            if (Category::find($categoryId)) {
                $query->where('category_id', $categoryId);
            }
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('author', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('isbn', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status === 'available') {
                $query->where('available_quantity', '>', 0);
            } elseif ($request->status === 'unavailable') {
                $query->where('available_quantity', '=', 0);
            }
        }

        $books = $query->paginate(12);
        $categories = Category::all();

        return view('all-books-admin', compact('books', 'categories'));
    }
}
