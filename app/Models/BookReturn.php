<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReturn extends Model
{
    protected $table = 'returns';
    protected $fillable = [
        'borrowing_id',
        'staff_id',
        'return_date',
        'book_condition',
        'notes',
    ];

    protected $casts = [
        'return_date' => 'datetime',
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function getConditionLabelAttribute()
    {
        return match($this->book_condition) {
            'good' => 'Baik',
            'damaged' => 'Rusak',
            'lost' => 'Hilang',
            default => 'Tidak Diketahui'
        };
    }
}
