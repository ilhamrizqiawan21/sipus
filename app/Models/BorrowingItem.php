<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['borrowing_transaction_id', 'book_copy_id', 'book_title_snapshot', 'book_isbn_snapshot', 'borrow_date', 'due_date', 'return_date', 'status', 'returned_condition_id', 'notes'])]
class BorrowingItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'borrowing_items';
    protected $casts = ['borrow_date' => 'date', 'due_date' => 'date', 'return_date' => 'date'];
    public function borrowingTransaction() { return $this->belongsTo(BorrowingTransaction::class); }
    public function bookCopy() { return $this->belongsTo(BookCopy::class); }
    public function returnedCondition() { return $this->belongsTo(BookCondition::class, 'returned_condition_id'); }
}
