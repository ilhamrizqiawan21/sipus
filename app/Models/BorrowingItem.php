<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['borrowing_transaction_id', 'book_copy_id', 'book_title_snapshot', 'inventory_code_snapshot', 'condition_at_borrow_id', 'borrow_date', 'due_date', 'return_date', 'condition_at_return_id', 'status', 'fine_amount', 'returned_by', 'notes', 'created_by', 'updated_by'])]
class BorrowingItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'borrowing_items';
    protected $casts = ['borrow_date' => 'date', 'due_date' => 'date', 'return_date' => 'date', 'fine_amount' => 'decimal:2'];
    public function borrowingTransaction() { return $this->belongsTo(BorrowingTransaction::class); }
    public function bookCopy() { return $this->belongsTo(BookCopy::class); }
    public function conditionAtBorrow() { return $this->belongsTo(BookCondition::class, 'condition_at_borrow_id'); }
    public function conditionAtReturn() { return $this->belongsTo(BookCondition::class, 'condition_at_return_id'); }
    public function fines() { return $this->hasMany(Fine::class, 'borrowing_item_id'); }
}
