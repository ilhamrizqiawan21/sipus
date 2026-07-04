<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'address', 'phone', 'email', 'website', 'is_active'])]
class Publisher extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'publishers';
    public function books() { return $this->hasMany(Book::class, 'publisher_id'); }
}
