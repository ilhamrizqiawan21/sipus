<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;

#[Fillable(['isbn','title','subtitle','category_id','publisher_id','language_id','publication_year','edition','pages','call_number','synopsis','cover_image','is_active'])]
class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'books';

    protected static function booted()
    {
        static::creating(function ($book) {
            if (empty($book->category_id)) {
                if (Schema::hasColumn('categories', 'slug')) {
                    $cat = Category::firstOrCreate(
                        ['name' => 'Uncategorized'],
                        ['slug' => 'uncategorized']
                    );
                } else {
                    $cat = Category::firstOrCreate(['name' => 'Uncategorized']);
                }
                $book->category_id = $cat->id;
            }
        });
    }
}
