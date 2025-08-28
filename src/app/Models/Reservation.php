<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Restaurant;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'date',
        'time',
        'number',
        'cancel_flug',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function scopeReservationSearch($query, $user_id, $restaurant_id, $cancel_flug_off)
    {
        $query->where('user_id', $user_id)->where('restaurant_id', $restaurant_id)->where('cancel_flug', $cancel_flug_off);
    }

    public function scopeMyReservationSearch($query, $user_id, $cancel_flug_off)
    {
        $query->where('user_id', $user_id)->where('cancel_flug', $cancel_flug_off);
    }
}
