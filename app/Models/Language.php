<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'code', 'is_active'])]
class Language extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'languages';
    public function books() { return $this->hasMany(Book::class, 'language_id'); }
}
