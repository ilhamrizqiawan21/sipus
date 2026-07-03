<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['member_code','nis_nisn','nip','member_type_id','class_id','name','gender','birth_date','address','phone','email','photo','card_number','join_date','is_active'])]
class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'members';
}
