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
        'review_rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function scopeReservationSearch($query, $user_id, $restaurant_id, $cancel_flug_off, $now)
    {
        $query->where('user_id', $user_id)->where('restaurant_id', $restaurant_id)->where('cancel_flug', $cancel_flug_off)->where('date', '>=', $now);
    }

    // 来店済の予約（過去の予約）は詳細ページでのコメント紹介用とするため、未来の予約とは分けて抽出。
    public function scopeVisitedSearch($query, $user_id, $restaurant_id, $cancel_flug_off, $now)
    {
        $query->where('user_id', $user_id)->where('restaurant_id', $restaurant_id)->where('cancel_flug', $cancel_flug_off)->where('date', '<', $now);
    }


    // マイページで表示する予約（来店済の予約で別々）はrestaurant_idを指定しないため別とする。
    public function scopeMyReservationSearch($query, $user_id, $cancel_flug_off, $now)
    {
        $query->where('user_id', $user_id)->where('cancel_flug', $cancel_flug_off)->where('date', '>=', $now)->orderBy('date', 'asc');
    }

    public function scopeMyVisitedSearch($query, $user_id, $cancel_flug_off, $now)
    {
        $query->where('user_id', $user_id)->where('cancel_flug', $cancel_flug_off)->where('date', '<', $now)->where('review_rating', null)->orderBy('date', 'asc');
          // 来店後（過去日付の予約レコード）、評価が登録されていないレコードのみを抽出する（マイページ表示用）
    }

}
