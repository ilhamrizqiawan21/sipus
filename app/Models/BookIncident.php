<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['book_copy_id', 'incident_type', 'description', 'reported_by', 'incident_date'])]
class BookIncident extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'book_incidents';
    public function bookCopy() { return $this->belongsTo(BookCopy::class); }
    public function reporter() { return $this->belongsTo(User::class, 'reported_by'); }
}
