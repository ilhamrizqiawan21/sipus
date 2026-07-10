<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['transaction_code', 'member_id', 'member_code_snapshot', 'member_name_snapshot', 'member_class_snapshot', 'member_type_snapshot', 'borrow_date', 'due_date', 'status', 'processed_by', 'notes', 'created_by', 'updated_by'])]
class BorrowingTransaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'borrowing_transactions';
    protected $casts = ['borrow_date' => 'date', 'due_date' => 'date'];
    public function member() { return $this->belongsTo(Member::class); }
    public function borrowingItems() { return $this->hasMany(BorrowingItem::class, 'borrowing_transaction_id'); }
    public function processedBy() { return $this->belongsTo(User::class, 'processed_by'); }
    public function fines() { return $this->hasManyThrough(Fine::class, BorrowingItem::class, 'borrowing_transaction_id', 'borrowing_item_id'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy() { return $this->belongsTo(User::class, 'updated_by'); }
}
