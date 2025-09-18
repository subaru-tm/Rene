@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}" >
@endsection

@section('content')
<div class="content">
    <div class="card">
        <div class="card-header">{{ __('Login') }}</div>

        <div class="card-body">
            <form method="POST" action="/login/store">
                @csrf

                <div class="card-body__input-group">
                    <img src="{{ asset('storage/email_img.png') }}" alt="" />
                    <span class="input-area">
                        <input type="text" class="form-input" name="email" value="{{ old('email') }}" placeholder="Email" autocomplete="email" autofocus>

                        <div class="invalid-feedback" role="alert">
                            @error('email')
                                <strong>{{ $message }}</strong>
                            @enderror
                        </div>
                    </span>
                </div>

                <div class="card-body__input-group">
                    <img src="{{ asset('storage/password_img.png') }}" alt="" />
                    <span class="input-area">
                        <input type="password" class="form-input" name="password" placeholder="Password" autocomplete="current-password">

                        <div class="invalid-feedback" role="alert">
                            @error('password')
                                <strong>{{ $message }}</strong>
                            @enderror
                        </div>
                    </span>
                </div>

                <div class="card-body__submit-button">
                    <button type="submit">ログイン</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
