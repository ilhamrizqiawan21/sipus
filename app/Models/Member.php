<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['member_code','nis_nisn','nip','member_type_id','class_id','name','gender','birth_date','address','phone','email','photo','card_number','join_date','is_active'])]
class Member extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'members';
    protected $casts = ['birth_date' => 'date', 'join_date' => 'date', 'is_active' => 'boolean'];

    public function memberType() { return $this->belongsTo(MemberType::class); }
    public function class() { return $this->belongsTo(Classes::class, 'class_id'); }
    public function borrowingTransactions() { return $this->hasMany(BorrowingTransaction::class, 'member_id'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy() { return $this->belongsTo(User::class, 'updated_by'); }
}
