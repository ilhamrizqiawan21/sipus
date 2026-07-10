<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['code', 'name', 'is_active', 'created_by', 'updated_by'])]
class BookCopyStatus extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'book_copy_statuses';
    protected $casts = ['is_active' => 'boolean'];
    public function bookCopies() { return $this->hasMany(BookCopy::class, 'status_id'); }
}
