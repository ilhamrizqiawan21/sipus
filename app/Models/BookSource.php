<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description', 'is_active', 'created_by', 'updated_by'])]
class BookSource extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'book_sources';
    protected $casts = ['is_active' => 'boolean'];
}
