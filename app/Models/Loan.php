<?php

namespace App\Models;

use Database\Factories\LoanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode_transaksi', 'member_id', 'recorded_by', 'tanggal_pinjam', 'batas_kembali', 'tanggal_selesai', 'status', 'catatan'])]
class Loan extends Model
{
    /** @use HasFactory<LoanFactory> */
    use HasFactory;

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(LoanItem::class);
    }

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'batas_kembali' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }
}
