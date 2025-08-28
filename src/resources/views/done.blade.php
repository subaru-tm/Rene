@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/done.css') }}" >
@endsection

@section('content')
<div class="card">
    <p class="card-message">ご予約ありがとうございます</p>
    <a class="card-button" href="/detail/{{ $restaurant_id }}">戻る</a>    
</div>
@endsection