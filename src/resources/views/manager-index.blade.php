@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/manager-index.css') }}" >
@endsection

@section('content')
<div class="content">
<div class="search">
    <form class="search-form" action="/manager/index/search" method="GET">
        @csrf
        <select class="search-form__select" name="area_id" onchange="this.form.submit()">
            <option value="" selected>All area</option>
            @foreach($areas as $area)
                <option value="{{ $area->id }}" 
                    @if( isset($area_id) )
                        @if( "$area->id" === $area_id )
                            selected
                        @endif
                    @endif
                >
                    {{ $area->name }}
                </option>
            @endforeach
        </select>
        <p class="search-form__separator">|</p>
        <select class="search-form__select" name="genre_id" onchange="this.form.submit()">
            <option value="" selected>All genre</option>
            @foreach($genres as $genre)
                <option value="{{ $genre->id }}"
                    @if( isset($genre_id) )
                        @if( "$genre->id" === $genre_id )
                            selected
                        @endif
                    @endif
                >
                {{ $genre->name }}</option>
            @endforeach
        </select>
        <p class="search-form__separator">|</p>
        <span class="search-form__input">
            <img src="{{ asset('storage/search_img.png') }}" alt="" />
            <input type="text" name="name" placeholder="Search ..." 
                @if( isset($name) )
                    value="{{ $name }}"
                @endif
            />
        </span>
    </form>
</div>

<a class="restaurant-register__link" href="/manager/new/register">新しい店舗を登録</a>
<a class="manager-notify__link" href="/manager/notify">利用者へのお知らせ</a>

<h2 class="incharge-title">{{ $user->name }}さんが代表者の店舗</h2>
<div class="restaurants-incharge">
    <div class="restaurants-incharge__review">
        <h4>ご担当店舗の評価平均</h4>
        <div class="restaurants-incharge__review-star">
            <?php $is = [1, 2, 3, 4, 5]; ?>
            @foreach( $is as $i )
                <p @if( $rating_average >= $i )
                    style="color: #FF8282;"
                    @endif>
                    ★
                </p>
            @endforeach
        </div>
        <div class="restaurants-incharge__review-score">
            {{ number_format($rating_average, 1) }}
        </div>
    </div>
    <div class="restaurants-incharge__list">
    @if(count($restaurantsInCharge) == 0)
        <!-- 検索条件の絞り込み等も含め、担当の店舗がない場合はメッセージのみ表示 -->
        ご担当の店舗が見つかりません
    @else
        @foreach($restaurantsInCharge as $restaurant)
            <div class="restaurant-card">
                <img src="{{ $restaurant->image_pass }}" alt="" />
                <div class="restaurant-card__info">
                    <div class="restaurant-card__info-name">
                        <p>{{ $restaurant->name }}</p>
                        <a class="restaurant-card__edit-link" href="/manager/{{ $restaurant->id }}/edit">店舗情報を更新</a>
                    </div>
                    <div class="restaurant-card__info-tag">
                        <span>#{{ $restaurant->area->name }}</span>
                        <span>#{{ $restaurant->genre->name }}</span>
                    </div>
                    <div class="restaurant-card__info-footer">
                        <a class="restaurant-card__status-link" href="/manager/{{ $restaurant->id }}/reservation/status" >予約状況を確認</a>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    </div>
    <h3 class="others-title">その他の店舗</h3>
    <div class="restaurants-other__list">
        @foreach($restaurantsOther as $restaurant)
            <div class="restaurant-card__other">
                <img src="{{ $restaurant->image_pass }}" alt="" />
                <div class="restaurant-card__info">
                    <div class="restaurant-card__info-name">
                        <p>{{ $restaurant->name }}</p>
                    </div>
                    <div class="restaurant-card__info-tag">
                        <span>#{{ $restaurant->area->name }}</span>
                        <span>#{{ $restaurant->genre->name }}</span>
                    </div>
                    <div class="restaurant-card__info-footer">
                        <a class="card-footer__edit-link" href="/manager/{{ $restaurant->id }}/edit">店舗情報を更新</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
</div>
@endsection