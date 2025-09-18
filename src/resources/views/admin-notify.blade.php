@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-notify.css') }}" >
@endsection

@section('content')
<div class="content">
    <div class="content-header">
        <a class="back-button" href="/admin/index"> ＜ </a>
        <h2>利用者へのお知らせ送信（管理者）</h2>
    </div>
    <form class="mail-form" action="/admin/notify/send" method="POST">
        @csrf
        <div class="mail-item">
            <label class="mail-to__label" for="to-select">宛先</label>
            <select class="mail-to__select" id="to-select" name="to" readonly >
                <option value="all" selected>全ユーザー</option>
            </select>
        </div>
        <div class="mail-item">
            <label class="mail-subject__label" for="subject-input">件名</label>
            <input class="mail-subject__input" id="subject-input" name="subject" />
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
