<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['code', 'name', 'location', 'capacity', 'is_active', 'created_by', 'updated_by'])]
class Bookshelf extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bookshelves';

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function bookCopies()
    {
        return $this->hasMany(BookCopy::class, 'bookshelf_id');
    }
}
