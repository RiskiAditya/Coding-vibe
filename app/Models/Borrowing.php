<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'return_requested_at',
        'status',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'return_requested_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function borrowingDetails()
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    public function return()
    {
        return $this->hasOne(BookReturn::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    public function calculateLateFine()
    {
        if ($this->return_date && $this->return_date > $this->due_date) {
            $daysLate = $this->return_date->diffInDays($this->due_date);
            return $daysLate * 1000; // Rp 1000 per day
        }
        return 0;
    }

    public function createLateFine()
    {
        $amount = $this->calculateLateFine();
        if ($amount > 0) {
            $this->fines()->create([
                'amount' => $amount,
                'reason' => 'Terlambat mengembalikan buku',
                'due_date' => $this->due_date,
            ]);
        }
    }

    public function createDamageFine($amount, $reason = 'Buku rusak')
    {
        $this->fines()->create([
            'amount' => $amount,
            'reason' => $reason,
            'due_date' => now()->addDays(7)->toDateString(), // Due in 7 days
        ]);
    }

    public function createLostFine($bookPrice = null)
    {
        $price = $bookPrice ?? $this->book->price ?? 50000; // Default Rp 50,000
        $this->fines()->create([
            'amount' => $price,
            'reason' => 'Buku hilang',
            'due_date' => now()->addDays(7)->toDateString(),
        ]);
    }

    public function returnBook($condition = 'good')
    {
        // Prevent double-processing if already returned
        if ($this->return_date || $this->status === 'returned') {
            return;
        }

        $this->update([
            'return_date' => now()->toDateString(),
            'status' => 'returned',
        ]);

        // Adjust book stock depending on condition
        $book = $this->book;
        if ($book) {
            if ($condition === 'lost') {
                // If the book is reported lost, reduce total quantity by 1 if possible
                if ($book->quantity > 0) {
                    $book->decrement('quantity');
                }

                // Ensure available_quantity does not exceed total quantity
                if ($book->available_quantity > $book->quantity) {
                    $book->available_quantity = $book->quantity;
                    $book->save();
                }
            } else {
                // For returned (good/late/damaged) increment available stock
                $book->increment('available_quantity');
            }
        }

        // Create fines if applicable
        if ($condition === 'late') {
            $this->createLateFine();
        } elseif ($condition === 'damaged') {
            $this->createDamageFine(25000, 'Buku rusak');
        } elseif ($condition === 'lost') {
            $this->createLostFine();
        }
    }

    public function getTotalFines()
    {
        return $this->fines()->sum('amount');
    }

    public function getUnpaidFines()
    {
        return $this->fines()->where('status', 'pending')->sum('amount');
    }
}
