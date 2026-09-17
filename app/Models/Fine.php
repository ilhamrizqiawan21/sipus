<?php

namespace App\Models;

use Database\Factories\FineFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['loan_item_id', 'paid_by', 'jenis', 'jumlah', 'status', 'dibayar_at', 'catatan'])]
class Fine extends Model
{
    /** @use HasFactory<FineFactory> */
    use HasFactory;

    public function loanItem(): BelongsTo
    {
        return $this->belongsTo(LoanItem::class);
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'dibayar_at' => 'datetime',
        ];
    }
}
