<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['book_procurement_id', 'book_id', 'quantity', 'unit_price', 'quantity_received'])]
class BookProcurementItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'book_procurement_items';
    protected $casts = ['unit_price' => 'decimal:2'];
    public function bookProcurement() { return $this->belongsTo(Procurement::class, 'book_procurement_id'); }
    public function book() { return $this->belongsTo(Book::class); }
}
