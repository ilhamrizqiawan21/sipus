<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['supplier_name','order_date','status','total','notes'])]
class Procurement extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'book_procurements';
    protected $casts = ['order_date' => 'date'];

    public function items() { return $this->hasMany(BookProcurementItem::class, 'book_procurement_id'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy() { return $this->belongsTo(User::class, 'updated_by'); }
}
