<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Reservation;
use App\Models\Favorite;
use App\Models\Restaurant;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'is_admin',
        'is_manager',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    public function scopeEmailSearch($query, $email_keyword)
    {
        if(!empty($email_keyword)) {
            $query->where('email', 'like', '%'.$email_keyword.'%');
        }
    }
    
    public function scopeNameSerch($query, $name_keyword)
    {
        if(!empty($name_keyword)) {
            $query->where('name', 'like', '%'.$name_keyword.'%');
        }
    }

    public function scopeManagerSearch($query)
    {
        $query->where('is_manager', true);
    }
}
