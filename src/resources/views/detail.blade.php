@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}" >
@endsection

@section('content')
<div class="content">
    <div class="restaurant-info">
        <div class="restaurant-info__header">
            <a class="back-button" href="/"> ＜ </a>
            <p class="restaurant-info__name">{{ $restaurant->name }}</p>
        </div>
        <img src="{{ $restaurant->image_pass }}" alt="" />
        <div class="restaurant-info__tag">
            <span>#{{ $restaurant->area->name }}</span>
            <span>#{{ $restaurant->genre->name }}</span>
        </div>
        <div class="restaurant-info__script">
            {{ $restaurant->description }}
        </div>
    </div>
    <div class="reservation">
        <h2>予約</h2>
        <form class="reservation-form" id="reservation-form" method="POST" action="/detail/{{ $restaurant->id }}/reservation" >
            @csrf
            <div class="reservation-form__input">
                <input class="reservation-form__input-date" type="date" name="date" value="{{ old('name') }}" />
                <input class="reservation-form__input-time" type="time" name="time" value="{{ old('time') }}" />
                <input class="reservation-form__input-number" type="text" name="number" value="{{ old('number') }}人" />
            </div>

            <button class="reservation-form__submit" type="submit">予約する</button>
        </form>

        <div class="reservation-record">
            @foreach($reservations as $reservation)
                <div class="reservation-card">
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
                            <td class="table-item">{{ $reservation->time }}</td>
                        </tr>
                        <tr class="reservation-card__table-row">
                            <th class="table-field">Number</th>
                            <td class="table-item">{{ $reservation->number }}人</td>
                        </tr>
                    </table>
                    <form class="reservation-card__cancel" action="/cancel/{{ $reservation->id }}" method="POST">
                        @csrf
                        <button class="reservation-card__cancel-button">キャンセルする</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
