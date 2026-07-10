<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['book_id', 'inventory_code', 'barcode', 'bookshelf_id', 'condition_id', 'source_id', 'status_id', 'procurement_item_id', 'acquisition_date', 'acquisition_price', 'notes', 'created_by', 'updated_by'])]
class BookCopy extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'book_copies';
    protected $casts = ['acquisition_date' => 'date', 'acquisition_price' => 'decimal:2'];

    public function book() { return $this->belongsTo(Book::class, 'book_id'); }
    public function status() { return $this->belongsTo(BookCopyStatus::class, 'status_id'); }
    public function condition() { return $this->belongsTo(BookCondition::class, 'condition_id'); }
    public function source() { return $this->belongsTo(BookSource::class, 'source_id'); }
    public function bookshelf() { return $this->belongsTo(Bookshelf::class, 'bookshelf_id'); }
    public function borrowingItems() { return $this->hasMany(BorrowingItem::class, 'book_copy_id'); }
    public function incidents() { return $this->hasMany(BookIncident::class, 'book_copy_id'); }
    public function fines() { return $this->hasManyThrough(Fine::class, BorrowingItem::class, 'book_copy_id', 'borrowing_item_id'); }
    public function stockOpnameDetails() { return $this->hasMany(StockOpnameDetail::class, 'book_copy_id'); }
}
