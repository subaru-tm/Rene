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
        <!-- 追加実装：来店済の店舗を予約よりも先に表示する（評価・コメント送信のため） -->
        @foreach($visitedReservations as $reservation)
        <div class="reservation-card">
            <div class="reservation-card__header">
                <img src="{{ asset('storage/clock-img.png') }}" alt="" />
                <p>来店{{ $i }}</p>
            </div>
            <div class="reservation-update__form">
                <table class="reservation-card__table">
                    <tr class="reservation-card__table-row">
                        <th class="table-field">Shop</th>
                        <td class="table-item">{{ $reservation->restaurant->name }}</td>
                    </tr>
                    <tr class="reservation-card__table-row">
                        <th class="table-field">Date</th>
                        <td class="table-item">
                            <input class="table-item__input-date" id="{{ $reservation->id }}__input-date" name="date" type="date" value="{{ $reservation->date }}" readonly />
                            <input type="hidden" name="reservation_id" value="{{ $reservation->id }}" />
                        </td>
                    </tr>
                    <tr class="reservation-card__table-row">
                        <th class="table-field">Time</th>
                        <td class="table-item">
                            <input class="table-item_input-time" id="{{ $reservation->id }}__input-time" name="time" type="time" value="{{ Carbon\Carbon::parse($reservation->time)->format('H:i') }}" readonly />
                        </td>
                    </tr>
                    <tr class="reservation-card__table-row">
                        <th class="table-field">Number</th>
                        <td class="table-item">
                            <input class="table-item_input-number" id="{{ $reservation->id }}__input-number" name="number" type="text" value="{{ $reservation->number }}人" readonly />
                        </td>
                    </tr>
                </table>
                <div class="reservation-card__button">
                    @livewire('review-modal', ['reservation_id' => $reservation->id])
                </div>
            </div>
        </div>
        <?php $i++; ?>
        @endforeach
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
            <form class="reservation-update__form" action="/update/{{ $reservation->id }}" method="POST">
                @csrf
                @method('PATCH')
                <table class="reservation-card__table">
                    <tr class="reservation-card__table-row">
                        <th class="table-field">Shop</th>
                        <td class="table-item">{{ $reservation->restaurant->name }}</td>
                    </tr>
                    <tr class="reservation-card__table-row">
                        <th class="table-field">Date</th>
                        <td class="table-item">
                            <input class="table-item__input-date" id="{{ $reservation->id }}__input-date" name="date" type="date" value="{{ $reservation->date }}" readonly />
                            <input type="hidden" name="reservation_id" value="{{ $reservation->id }}" />
                        </td>
                    </tr>
                    <tr class="reservation-card__table-row">
                        <th class="table-field">Time</th>
                        <td class="table-item">
                            <input class="table-item_input-time" id="{{ $reservation->id }}__input-time" name="time" type="time" value="{{ Carbon\Carbon::parse($reservation->time)->format('H:i') }}" readonly />
                        </td>
                    </tr>
                    <tr class="reservation-card__table-row">
                        <th class="table-field">Number</th>
                        <td class="table-item">
                            <input class="table-item_input-number" id="{{ $reservation->id }}__input-number" name="number" type="text" value="{{ $reservation->number }}人" readonly />
                        </td>
                    </tr>
                </table>
                <div class="reservation-card__button">
                    <button class="reservation-edit__button" id="{{ $reservation->id }}__edit" type="button">
                        予約を変更する
                    </button>
                    <button class="reservation-update__button" id="{{ $reservation->id }}__update" type="submit">
                        予約を更新する
                    </button>
                    <button class="edit-cancel__button" id="{{ $reservation->id }}__edit-cancel" type="button">
                        戻る
                    </button>
                </div>
            </form>
                <div class="reservation-card__buttons">
                    @livewire('qrcode-show', [
                        'reservation_id' => $reservation->id
                    ])
                    <form class="reservation-card__payment" action="/stripe/index" method="GET">
                        @csrf
                        <button class="reservation-card__payment-button">決済する</button>
                    </form>
                </div>
        </div>
        <?php $i++; ?>
        <script>
            const edit_reservation_btn_{{$i}} = document.getElementById('{{ $reservation->id }}__edit');
            const update_reservation_btn_{{$i}} = document.getElementById('{{ $reservation->id }}__update');
            const cancel_edit_btn_{{$i}} = document.getElementById('{{ $reservation->id }}__edit-cancel');

            edit_reservation_btn_{{$i}}.addEventListener('click', (e) => {
                e.target.style.display = "none";
                update_reservation_btn_{{$i}}.style.display = "unset";
                cancel_edit_btn_{{$i}}.style.display = "unset";
                const input_date = document.getElementById('{{ $reservation->id }}__input-date');
                input_date.readOnly = false;
                input_date.style.color = '#000000';
                input_date.style.backgroundColor = '#FFFFFF';
                input_date.focus();
                const input_time = document.getElementById('{{ $reservation->id }}__input-time');
                input_time.readOnly = false;
                input_time.style.color = '#000000';
                input_time.style.backgroundColor = '#FFFFFF';
                const input_number = document.getElementById('{{ $reservation->id }}__input-number');
                input_number.readOnly = false;
                input_number.style.color = '#000000';
                input_number.style.backgroundColor = '#FFFFFF';
            });

            cancel_edit_btn_{{$i}}.addEventListener('click', (e) => {
                e.target.style.display = "none";
                edit_reservation_btn_{{$i}}.style.display = "unset";
                update_reservation_btn_{{$i}}.style.display = "none";
                const input_date = document.getElementById('{{ $reservation->id }}__input-date');
                input_date.readOnly = true;
                input_date.style.color = "unset";
                input_date.style.backgroundColor = "unset";
                const input_time = document.getElementById('{{ $reservation->id }}__input-time');
                input_time.readOnly = true;
                input_time.style.color = "unset";
                input_time.style.backgroundColor = "unset";
                const input_number = document.getElementById('{{ $reservation->id }}__input-number');
                input_number.readOnly = true;
                input_number.style.color = "unset";
                input_number.style.backgroundColor = "unset";
            });
        </script>

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
