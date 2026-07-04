<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['setting_key', 'setting_value', 'setting_type', 'description'])]
class Setting extends Model
{
    use HasFactory;
    protected $table = 'settings';
    public $timestamps = false;
    public static function getAll() { return self::pluck('setting_value', 'setting_key')->toArray(); }
    public static function getSetting($key, $default = null) { $s = self::where('setting_key', $key)->first(); return $s ? $s->setting_value : $default; }
}
