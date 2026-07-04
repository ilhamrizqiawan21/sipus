<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description', 'fine_multiplier', 'is_active'])]
class BookCondition extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'book_conditions';
    public function bookCopies() { return $this->hasMany(BookCopy::class, 'condition_id'); }
}
