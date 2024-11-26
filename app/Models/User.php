<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'no_identitas',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }

    public function wishlist()
    {
        return $this->belongsToMany(Book::class, 'wishlist')->withTimestamps();
    }
}
