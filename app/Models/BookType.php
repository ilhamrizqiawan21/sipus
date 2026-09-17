<?php

namespace App\Models;

use Database\Factories\BookTypeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'kode', 'deskripsi', 'aktif'])]
class BookType extends Model
{
    /** @use HasFactory<BookTypeFactory> */
    use HasFactory;

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'jenis_buku_id');
    }

    protected function casts(): array
    {
        return ['aktif' => 'boolean'];
    }
}
