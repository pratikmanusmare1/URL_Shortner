<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function urls()
    {
        return $this->hasMany(Url::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }
}
