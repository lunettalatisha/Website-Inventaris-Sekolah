<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'password',
        'role',
        'name',
        'email',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
