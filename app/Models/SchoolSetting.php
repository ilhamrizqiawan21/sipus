<?php

namespace App\Models;

use Database\Factories\SchoolSettingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['school_name', 'logo_path', 'address', 'education_level', 'teacher_loan_days', 'student_loan_days', 'teacher_loan_limit', 'student_loan_limit', 'late_fee_per_day', 'lost_book_fee', 'damaged_book_fee', 'member_number_format', 'loan_number_format', 'fine_blocks_loan'])]
class SchoolSetting extends Model
{
    /** @use HasFactory<SchoolSettingFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'teacher_loan_days' => 'integer',
            'student_loan_days' => 'integer',
            'teacher_loan_limit' => 'integer',
            'student_loan_limit' => 'integer',
            'late_fee_per_day' => 'decimal:2',
            'lost_book_fee' => 'decimal:2',
            'damaged_book_fee' => 'decimal:2',
            'fine_blocks_loan' => 'boolean',
        ];
    }
}
