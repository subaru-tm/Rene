@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/manager-notify.css') }}" >
@endsection

@section('content')
<div class="content">
    <div class="content-header">
        <a class="back-button" href="/manager/index"> ＜ </a>
        <h2>利用者へのお知らせ送信（店舗代表者）</h2>
    </div>
    <form class="mail-form" action="/manager/notify/send" method="POST">
        @csrf
        <div class="mail-item">
            <label class="mail-to__label" for="to-select">宛先</label>
            <select class="mail-to__select" id="to-select" name="to" >
                <option value="all" >全ユーザー</option>
                @if( isset($restaurant) )
                    <option value="visited">来店履歴のあるお客様</option>
                @endif
                @if( isset($user) )
                    <option value="individual" selected>{{ $user->name }} 様</option>
                @endif

            </select>
            @if( isset($restaurant) )
                <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}" />
            @endif
            @if( isset($user) )
                <input type="hidden" name="user_id" value="{{ $user->id }}" />
            @endif

        </div>
        <div class="mail-item">
            <label class="mail-subject__label" for="subject-input">件名</label>
            <input class="mail-subject__input" id="subject-input" name="subject" @if( isset($restaurant) )
                value="{{ $restaurant->name }}からのお知らせ"
            @endif />
        </div>
        <div class="mail-item">
            <label class="mail-content__label" for="content-text">本文</label>
            <textarea class="mail-content__input" id="content-text" name="content"></textarea>
        </div>
        <button type="submit">送信</button>
        @if(session('status'))
            <div class="mail-form__submit-status">
                {{ session('status') }}
            </div>
        @endif
    </form>
</div>
@endsection