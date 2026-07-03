<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    use HasFactory;

    protected $table = 'procurements';
    protected $fillable = ['supplier_name','order_date','status','total','notes','items'];

    protected $casts = [
        'items' => 'array',
        'order_date' => 'date',
    ];
}
