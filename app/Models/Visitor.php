<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'email', 'phone', 'institution', 'visit_date', 'visit_purpose_id', 'notes'])]
class Visitor extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'visitors';
    protected $casts = ['visit_date' => 'date'];
    public function visitPurpose() { return $this->belongsTo(VisitPurpose::class); }
}
