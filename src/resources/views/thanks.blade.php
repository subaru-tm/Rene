@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}" >
@endsection

@section('content')
<div class="content">
    <div class="card">
        <div class="card-body">
            <div class="card-body__thanks">
                会員登録ありがとうございます
            </div>
            <a href="/login">ログインする</a>
        </div>
    <div>
</div>
@endsection