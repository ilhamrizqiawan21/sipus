<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'level', 'description', 'is_active'])]
class Classes extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'classes';
    public function members() { return $this->hasMany(Member::class, 'class_id'); }
}
