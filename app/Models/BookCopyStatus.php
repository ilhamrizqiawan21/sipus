<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description', 'is_available', 'is_active'])]
class BookCopyStatus extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'book_copy_statuses';
    public function bookCopies() { return $this->hasMany(BookCopy::class, 'status_id'); }
}
