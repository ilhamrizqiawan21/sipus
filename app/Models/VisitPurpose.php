<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description', 'is_active'])]
class VisitPurpose extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'visit_purposes';
    public function visitors() { return $this->hasMany(Visitor::class); }
}
