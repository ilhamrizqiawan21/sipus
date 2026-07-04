<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['book_id', 'barcode', 'location', 'status_id', 'condition_id', 'acquisition_date', 'notes'])]
class BookCopy extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'book_copies';
    protected $casts = ['acquisition_date' => 'date'];

    public function book() { return $this->belongsTo(Book::class, 'book_id'); }
    public function status() { return $this->belongsTo(BookCopyStatus::class, 'status_id'); }
    public function condition() { return $this->belongsTo(BookCondition::class, 'condition_id'); }
    public function borrowingItems() { return $this->hasMany(BorrowingItem::class, 'book_copy_id'); }
    public function incidents() { return $this->hasMany(BookIncident::class, 'book_copy_id'); }
    public function fines() { return $this->hasMany(Fine::class, 'book_copy_id'); }
    public function stockOpnameDetails() { return $this->hasMany(StockOpnameDetail::class, 'book_copy_id'); }
}
