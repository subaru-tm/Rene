@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">{{ __('登録していただいたメールアドレスに認証メールを送付しました。') }}</div>

        <div class="card-body">
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('認証メールを再送しました。') }}
                </div>
            @endif

            {{ __('メール認証を完了してください。') }}

            <form class="resend-form" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="resend-form__button">{{ __('認証メールを再送する') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
