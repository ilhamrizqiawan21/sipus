<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['import_type', 'file_name', 'total_rows', 'successful_rows', 'failed_rows', 'status', 'error_details', 'imported_by'])]
class ImportLog extends Model
{
    use HasFactory;
    protected $table = 'import_logs';
    public $timestamps = false;
    protected $casts = ['created_at' => 'datetime', 'error_details' => 'json'];
    public function importedBy() { return $this->belongsTo(User::class, 'imported_by'); }
}
