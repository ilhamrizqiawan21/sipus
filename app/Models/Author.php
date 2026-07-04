<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'biography', 'birth_date', 'is_active'])]
class Author extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'authors';
    public function books() { return $this->belongsToMany(Book::class, 'book_authors', 'author_id', 'book_id'); }
}
