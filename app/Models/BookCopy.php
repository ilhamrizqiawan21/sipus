<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    use HasFactory;

    protected $table = 'book_copies';
    protected $fillable = ['book_id', 'barcode', 'location', 'status'];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
