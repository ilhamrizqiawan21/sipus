<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['stock_opname_id', 'book_copy_id', 'expected_condition', 'actual_condition', 'notes'])]
class StockOpnameDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'stock_opname_details';
    public function stockOpname() { return $this->belongsTo(StockOpname::class); }
    public function bookCopy() { return $this->belongsTo(BookCopy::class); }
}
