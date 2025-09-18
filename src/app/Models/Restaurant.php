<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use App\Models\Favorite;
use App\Models\User;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'genre_id',
        'user_id',
        'name',
        'description',
        'image_pass',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function scopeAreaSearch($query, $area_id)
    {
        if(!empty($area_id)) {
            $query->where('area_id', $area_id);
        }
    }

    public function scopeGenreSearch($query, $genre_id)
    {
        if(!empty($genre_id)) {
            $query->where('genre_id', $genre_id);
        }
    }

    public function scopeNameSearch($query, $name)
    {
        if(!empty($name)) {
            $query->where('name', 'like', '%'.$name.'%');
        }
    }

    // 店舗代表者用の管理画面で、担当店舗、それ以外を抽出
    public function scopeInCharge($query, $user_id)
    {
        if(!empty($user_id)) {
            $query->where('user_id', $user_id);
        }
    }

    public function scopeOther($query, $user_id)
    {
        if(!empty($user_id)) {
            $query->where('user_id', '<>', $user_id)->orWhereNull('user_id');
        }
    }

}
