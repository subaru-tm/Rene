@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}" >
@endsection

@section('content')
<?php use Illuminate\Support\Facades\Auth; ?>
<div class="content">
<div class="search">
    <form class="search-form" action="/search" method="GET">
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

<div class="restaurants-list">
    @foreach($restaurants as $restaurant)
        <div class="restaurant-card">
            <img src="{{ $restaurant->image_pass }}" alt="" />
            <div class="restaurant-card__info">
                <div class="restaurant-card__info-name">
                    {{ $restaurant->name }}
                </div>
                <div class="restaurant-card__info-tag">
                    <span>#{{ $restaurant->area->name }}</span>
                    <span>#{{ $restaurant->genre->name }}</span>
                </div>
                <div class="restaurant-card__info-footer">
                    <a class="restaurant-card__detail-link" href="/detail/{{ $restaurant->id }}" >詳しくみる</a>
                    <?php   $loggedin = Auth::check();
                            $favorite_displayed = 0;   ?>
                    @if (!$loggedin)
                        <!-- ログインしていない場合、一律でお気に入り未登録状態を表示 -->
                        <form class="restaurant-card__favorite-form" method="POST" 
                            action="favorite/{{ $restaurant->id }}/on" >
                            @csrf
                            <button class="favorite-form__button" type="submit">
                                <img src="{{ asset('storage/favorite_off.png') }}" alt="" />
                            </button>
                        </form>
                    @else
                      @foreach( $restaurant->favorites as $favorite )
                        @if( $favorite->restaurant_id == $restaurant->id )
                            @if( $favorite->favorite_flug == '1')
                                <!-- お気に入り登録済の場合、解除のformとする -->
                                <form class="restaurant-card__favorite-form" method="POST" 
                                    action="favorite/{{ $restaurant->id }}/off" >
                                    @csrf
                                    <button class="favorite-form__button" type="submit">
                                        <img src="{{ asset('storage/favorite_on.png') }}" alt="" />
                                    </button>
                                </form>
                                <?php $favorite_displayed = 1; ?>
                            @else
                                <!-- お気に入りレコードが存在し解除済の場合、登録のformとする -->
                                <form class="restaurant-card__favorite-form" method="POST" 
                                    action="favorite/{{ $restaurant->id }}/on" >
                                    @csrf
                                    <button class="favorite-form__button" type="submit">
                                        <img src="{{ asset('storage/favorite_off.png') }}" alt="" />
                                    </button>
                                </form>
                                <?php $favorite_displayed = 1; ?>
                            @endif
                        @endif
                      @endforeach
                      @if( $favorite_displayed == 0 )
                        <!-- favoritesテーブルに該当店レコードが存在しない場合、foreachに入らない。この場合は登録form -->
                        <form class="restaurant-card__favorite-form" method="POST" 
                            action="favorite/{{ $restaurant->id }}/on" >
                            @csrf
                            <button class="favorite-form__button" type="submit">
                                <img src="{{ asset('storage/favorite_off.png') }}" alt="" />
                            </button>
                        </form>
                      @endif
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
</div>
@endsection