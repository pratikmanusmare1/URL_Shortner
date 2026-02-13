<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'inviter_id', 'company_id', 'email', 'role', 'token', 'status', 'accepted_at'
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public static function generateToken(): string
    {
        return Str::random(40);
    }


    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
