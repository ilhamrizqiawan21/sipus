<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['opname_code', 'opname_date', 'start_date', 'end_date', 'status', 'notes'])]
class StockOpname extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'stock_opnames';
    protected $casts = ['opname_date' => 'date', 'start_date' => 'date', 'end_date' => 'date'];
    public function details() { return $this->hasMany(StockOpnameDetail::class, 'stock_opname_id'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy() { return $this->belongsTo(User::class, 'updated_by'); }
}
