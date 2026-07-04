<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['book_id', 'author_id'])]
class BookAuthor extends Model
{
    protected $table = 'book_authors';
    public $timestamps = false;
    public function book() { return $this->belongsTo(Book::class); }
    public function author() { return $this->belongsTo(Author::class); }
}
