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
        @if ($rating_average == 0)
            <!-- まだ評価がない場合は何も表示しない -->
        @else
            <div class="restaurant-info__review">
                <!-- 評価の★を表示をforeachで繰り返し表示するための配列を定義 -->
                <?php $is = [1, 2, 3, 4, 5]; ?>
                <div class="review-rating__average">
                    <p>来店者の評価（平均）</p>
                    <span class="review-rating__average-star">
                        @foreach( $is as $i )
                            <input class="rating__input" id="average-star$i" name="rating" type="radio" value="$i"
                                @if ($rating_average >= $i)
                                    checked
                                @endif
                                disabled>
                            <label class="rating__label-average" for="average-star$i"
                                @if ($rating_average >= $i)
                                    style="color: #FF8282;"
                                @endif
                            >★</label>
                        @endforeach
                    </span>
                    <p class="review-rating__average-numeric">
                        {{ number_format($rating_average, 1) }}
                    </p>
                </div>
                <div class="review-ratings__comments">
                    <p class="review-ratings__comments-title">
                        みんなのコメント
                    </p>
                    @foreach( $visited_reservations as $reservation )
                        @if( !is_null($reservation->review_rating))
                            <div class="review-card">
                                <span>{{ $reservation->user->name }}</span>
                                @foreach( $is as $i )
                                    <input class="rating__input" id="star$i" name="rating" type="radio" value="$i"
                                        @if ($reservation->review_rating >= $i)
                                            checked
                                        @endif
                                        disabled>
                                    <label class="rating__label" for="star$i"
                                        @if ($reservation->review_rating >= $i)
                                            style="color: #FF8282;"
                                        @endif
                                    >★</label>
                                @endforeach
                                <p class="review-card__comment">{{ $reservation->comment }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <div class="reservation">
        <h2>予約</h2>
        <form class="reservation-form" id="reservation-form" method="POST" action="/detail/{{ $restaurant->id }}/reservation" >
            @csrf
            <div class="reservation-form__input">
                <input class="reservation-form__input-date" type="date" name="date" value="{{ old('name') }}" />
                <div class="invalid-feedback" role="alert">
                    @error('date')
                        <strong>{{ $message }}</strong>
                    @enderror
                </div>
                <input class="reservation-form__input-time" type="time" name="time" value="{{ old('time') }}" />
                <div class="invalid-feedback" role="alert">
                    @error('time')
                        <strong>{{ $message }}</strong>
                    @enderror
                </div>
                <input class="reservation-form__input-number" type="text" name="number" 
                    @if( $errors->has('number') )
                        value="{{ old('number') }}"
                    @else
                        value="人"
                    @endif />
                <div class="invalid-feedback" role="alert">
                    @error('number')
                        <strong>{{ $message }}</strong>
                    @enderror
                </div>
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
                            <td class="table-item">{{ Carbon\Carbon::parse($reservation->time)->format('H:i') }}</td>
                        </tr>
                        <tr class="reservation-card__table-row">
                            <th class="table-field">Number</th>
                            <td class="table-item">{{ $reservation->number }}人</td>
                        </tr>
                    </table>
                    <div class="reservation-card__buttons">
                        @livewire('qrcode-show', [
                            'reservation_id' => $reservation->id
                        ])
                        <form class="reservation-card__payment" action="/stripe/index" method="GET">
                            @csrf
                            <button class="reservation-card__payment-button">決済する</button>
                        </form>
                        <form class="reservation-card__cancel" action="/cancel/{{ $reservation->id }}" method="POST">
                            @csrf
                            <button class="reservation-card__cancel-button">キャンセルする</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
