<?php

namespace App\Models;

use Database\Factories\SchoolYearFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'semester', 'is_aktif', 'mulai', 'selesai'])]
class SchoolYear extends Model
{
    /** @use HasFactory<SchoolYearFactory> */
    use HasFactory;

    public function classes(): HasMany
    {
        return $this->hasMany(Classroom::class, 'school_year_id');
    }

    protected function casts(): array
    {
        return [
            'is_aktif' => 'boolean',
            'mulai' => 'date',
            'selesai' => 'date',
        ];
    }
}
