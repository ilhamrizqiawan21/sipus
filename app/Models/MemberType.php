<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description', 'max_borrow_limit', 'borrow_duration_days', 'is_active'])]
class MemberType extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'member_types';
    public function members() { return $this->hasMany(Member::class, 'member_type_id'); }
}
