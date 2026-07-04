<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['borrowing_transaction_id', 'book_copy_id', 'reason', 'amount', 'status', 'payment_date', 'notes'])]
class Fine extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'fines';
    protected $casts = ['amount' => 'decimal:2', 'payment_date' => 'date'];
    public function borrowingTransaction() { return $this->belongsTo(BorrowingTransaction::class); }
    public function bookCopy() { return $this->belongsTo(BookCopy::class); }
}
