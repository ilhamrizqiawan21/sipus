<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description', 'is_active', 'created_by', 'updated_by'])]
class BookCondition extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'book_conditions';
    protected $casts = ['is_active' => 'boolean'];
    public function bookCopies() { return $this->hasMany(BookCopy::class, 'condition_id'); }
}
