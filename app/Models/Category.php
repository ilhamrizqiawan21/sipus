<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description', 'is_active'])]
class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'categories';
    protected $casts = ['is_active' => 'boolean'];

    public function books() { return $this->hasMany(Book::class, 'category_id'); }
}
