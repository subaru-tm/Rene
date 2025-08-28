@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}" >
@endsection

@section('content')
<div class="content">
    <div class="card">
        <div class="card-header">Registration</div>

        <div class="card-body">
            <form method="POST" action="/register">
                @csrf

                <div class="card-body__input-group">
                    <img src="{{ asset('storage/username_img.png') }}" alt="" />
                    <span class="input-area">
                        <input type="text" class="form-input" name="name" value="{{ old('name') }}" placeholder="Username" autocomplete="name" autofocus>

                        <div class="invalid-feedback" role="alert">
                            @error('name')
                                <strong>{{ $message }}</strong>
                            @enderror
                        </div>
                    </span>
                </div>

                <div class="card-body__input-group">
                    <img src="{{ asset('storage/email_img.png') }}" alt="" />
                    <span class="input-area">
                        <input type="text" class="form-input" name="email" value="{{ old('email') }}" placeholder="Email" autocomplete="email">

                        <div class="invalid-feedback">
                            @error('email')
                                <strong>{{ $message }}</strong>
                           @enderror
                        </div>
                    </span>
                </div>

                <div class="card-body__input-group">
                    <img src="{{ asset('storage/password_img.png') }}" alt="" />
                    <span class="input-area">
                        <input type="password" class="form-input" name="password" placeholder="Password" autocomplete="new-password">

                        <div class="invalid-feedback" role="alert">
                            @error('password')
                                <strong>{{ $message }}</strong>
                            @enderror
                        </div>
                    </span>
                </div>

                <div class="card-body__submit-button">
                    <button type="submit">登録</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
