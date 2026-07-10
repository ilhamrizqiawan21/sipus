<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['key', 'value', 'type', 'group', 'description', 'updated_by'])]
class Setting extends Model
{
    use HasFactory;
    protected $table = 'settings';

    public static function getAll() { return self::pluck('value', 'key')->toArray(); }
    public static function getSetting($key, $default = null) { $s = self::where('key', $key)->first(); return $s ? $s->value : $default; }
}
