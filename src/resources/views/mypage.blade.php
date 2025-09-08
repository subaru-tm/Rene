@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}" >
@endsection

@section('content')
<?php 
use App\Models\Area;
use App\Models\Genre;
?>

<div class="content">
    <div class="reservation">
        <h1>----</h1>
        <h2>予約状況</h2>
        <?php $i=1; ?>
        @foreach($reservations as $reservation)
        <div class="reservation-card">
            <div class="reservation-card__header">
                <img src="{{ asset('storage/clock-img.png') }}" alt="" />
                <p>予約{{ $i }}</p>
                <form class="reservation-cancel" action="/cancel/{{ $reservation->id }}" method="POST">
                    @csrf
                    <button type="submit">
                        <img src="{{ asset('storage/cancel-img.png') }}" alt="" />
                    </button>
                </form>
            </div>
            <table class="reservation-card__table">
                <tr class="reservation-card__table-row">
                    <th class="table-field">Shop</th>
                    <td class="table-item">{{ $reservation->restaurant->name }}</td>
                </tr>
                <tr class="reservation-card__table-row">
                    <th class="table-field">Date</th>
                    <td class="table-item">{{ $reservation->date }}</td>
                </tr>
                <tr class="reservation-card__table-row">
                    <th class="table-field">Time</th>
                    <td class="table-item">{{ Carbon\Carbon::parse($reservation->time)->format('H:i') }}</td>
                </tr>
                <tr class="reservation-card__table-row">
                    <th class="table-field">Number</th>
                    <td class="table-item">{{ $reservation->number }}人</td>
                </tr>
            </table>
        </div>
        <?php $i++; ?>
        @endforeach

    </div>
    <div class="favorite">
        <h1>{{ $user->name }}さん</h1>
        <h2>お気に入り店舗</h2>
        <div class="favorite-restaurants">
            @foreach( $favorites as $favorite )
            <div class="favorite-card">
                <img src="{{ $favorite->restaurant->image_pass }}" alt="" />
                <div class="favorite-card__info">
                    <div class="favorite-card__info-name">
                        {{ $favorite->restaurant->name }}
                    </div>
                    <div class="favorite-card__info-tag">
                        <?php 
                            $area_id = $favorite->restaurant->area_id;
                            $area = Area::find($area_id);

                            $genre_id = $favorite->restaurant->genre_id;
                            $genre = Genre::find($genre_id);
                        ?>
                        <span>#{{ $area->name }}</span>
                        <span>#{{ $genre->name }}</span>
                    </div>
                    <div class="favorite-card__info-footer">
                        <a class="favorite-card__detail-link" href="/detail/{{ $favorite->restaurant_id }}" >詳しくみる</a>
                        <!-- お気に入り登録済のみ表示される前提。解除formのみとする -->
                        <form class="restaurant-card__favorite-form" method="POST" 
                        action="favorite/{{ $favorite->restaurant_id }}/off" >
                            @csrf
                            <button class="favorite-form__button" type="submit">
                                <img src="{{ asset('storage/favorite_on.png') }}" alt="" />
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
