<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['borrowing_item_id', 'borrowing_transaction_id', 'book_copy_id', 'fine_type', 'amount', 'reason', 'status', 'paid_amount', 'paid_date', 'payment_date', 'waived_by', 'waived_reason', 'created_by', 'updated_by'])]
class Fine extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'fines';
    protected $casts = ['amount' => 'decimal:2', 'paid_amount' => 'decimal:2', 'paid_date' => 'date', 'payment_date' => 'date'];
    public function borrowingItem() { return $this->belongsTo(BorrowingItem::class); }
    public function waivedBy() { return $this->belongsTo(User::class, 'waived_by'); }
}
