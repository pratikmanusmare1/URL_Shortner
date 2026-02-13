<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_id', 'original_url', 'short_code', 'clicks'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public static function encodeBase62(int $num): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($chars);
        $out = '';
        while ($num > 0) {
            $out = $chars[$num % $base] . $out;
            $num = (int) floor($num / $base);
        }
        return $out === '' ? '0' : $out;
    }

    protected static function booted()
    {
        static::created(function ($url) {
            $code = self::encodeBase62($url->id);
            $url->update(['short_code' => $code]);
        });
    }
}
