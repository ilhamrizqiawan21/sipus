<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

#[Fillable(['isbn','title','subtitle','category_id','publisher_id','language_id','publication_year','edition','pages','call_number','synopsis','cover_image','is_active'])]
class Book extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'books';
    protected $casts = ['is_active' => 'boolean'];

    protected static function booted() {
        static::creating(function ($book) {
            if (empty($book->category_id)) {
                $cat = Category::firstOrCreate(['name' => 'Uncategorized']);
                $book->category_id = $cat->id;
            }
        });
    }

    public function category() { return $this->belongsTo(Category::class); }
    public function publisher() { return $this->belongsTo(Publisher::class); }
    public function language() { return $this->belongsTo(Language::class); }
    public function authors() { return $this->belongsToMany(Author::class, 'book_authors', 'book_id', 'author_id'); }
    public function bookCopies() { return $this->hasMany(BookCopy::class, 'book_id'); }
    public function bookSources() { return $this->hasMany(BookSource::class); }
    public function procurementItems() { return $this->hasMany(BookProcurementItem::class, 'book_id'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy() { return $this->belongsTo(User::class, 'updated_by'); }
}
