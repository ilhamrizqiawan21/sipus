<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'action', 'table_name', 'record_id', 'old_values', 'new_values', 'ip_address', 'user_agent', 'notes'])]
class ActivityLog extends Model
{
    use HasFactory;
    protected $table = 'activity_logs';
    public $timestamps = false;
    protected $casts = ['created_at' => 'datetime', 'old_values' => 'json', 'new_values' => 'json'];
    public function user() { return $this->belongsTo(User::class); }
}
