@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservation-check.css') }}" >
@endsection

@section('content')
<div class="header-link">
    <a class="manager-notify__link" href="/manager/{{ $restaurant->id }}/notify/null">利用者へのお知らせ</a>
    <!-- 現在予約は無いけど過去来店者への通知は店舗からしたい場合のリンク -->
</div>
<div class="content">
    <div class="restaurant-info">
        <div class="restaurant-info__header">
            <a class="back-button" href="/manager/index"> ＜ </a>
            <p class="restaurant-info__name">{{ $restaurant->name }}　予約状況確認</p>
        </div>
        <img src="{{ $restaurant->image_pass }}" alt="" />
    </div>
    <div class="reservation">
        <h2>予約 状況</h2>

        <div class="reservation-record">
            @foreach($reservations as $reservation)
                <div class="reservation-card">
                    <table class="reservation-card__table">
                        <tr class="reservation-card__table-row">
                            <th class="table-field">User</th>
                            <td class="table-item">{{ $reservation->user->name }}</td>
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
                    <a class="reservation-card__notify-link" href="/manager/{{ $reservation->restaurant_id }}/notify/{{ $reservation->user_id }}" >お知らせを送る</a>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
