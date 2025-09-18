@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/restaurant-edit.css') }}" >
@endsection

@section('content')
<div class="content">
    <div class="content-header">
        <a class="back-button" href="/manager/index"> ＜ </a>
        <h2>{{ $restaurant->name }} 店舗の更新</h2>
    </div>
    <form class="restaurant-register__form" action="/manager/{{ $restaurant->id }}/update" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="restaurant-register__form-item">
            <input class="form-item__name-input" name="name" value="{{ $restaurant->name }}" placeholder="店舗名" />
            <div class="input-feild__alert">
                @error('name')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="restaurant-register__form-item">
            @if(!is_null( $restaurant->user_id ))
                @livewire('receive-user-selected', [
                    'user' => $restaurant->user
                ])
            @else
                @livewire('receive-user-selected')
            @endif
        </div>
        <div class="restaurant-register__form-item">
            <label class="form-item__img-label" for="img-flie__input">店舗画像を選択</label>
            <input class="form-item__img-attach" id="img-flie__input" name="img_file" type="file" />
            @if( asset( $restaurant->image_pass ) )
                <img src="{{ asset($restaurant->image_pass) }}" alt="店舗画像が選ばれていません" id="img-view"/>
            @else
                <img src="{{ $restaurant->image_pass }}" alt="店舗画像が選ばれていません" id="img-view" />
            @endif

            <div class="input-feild__alert">
                @error('img_file')
                    {{ $message }}
                @enderror
            </div>
            <!-- 選択した画像を即プレビュー表示するためのscript -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $('#img-flie__input').on('change', function() {
                    var $fr = new FileReader();
                    $fr.onload = function() {
                        $('#img-view').attr('src', $fr.result);
                    }
                    $fr.readAsDataURL(this.files[0]);
                });
            </script>
        </div>
        <div class="restaurant-register__area-genre">
            <select class="form-item__area-select" name="area_id">
                <option value="" selected>Area</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}"
                        @if ( $area->id == $restaurant->area_id )
                            selected
                        @endif
                    >{{ $area->name }}</option>
                @endforeach
            </select>
            <div class="input-feild__alert">
                @error('area_id')
                    {{ $message }}
                @enderror
            </div>

            <select class="form-item__genre-select" name="genre_id">
                <option value="" selected>Genre</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}"
                        @if ( $genre->id == $restaurant->genre_id )
                            selected
                        @endif
                    >{{ $genre->name }}</option>
                @endforeach
            </select>
            <div class="input-feild__alert">
                @error('genre_id')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="restaurant-register__form-item">
            <textarea class="form-item__description-input" name="description" placeholder="店舗紹介" >{{ $restaurant->description }}</textarea>
            <div class="input-feild__alert">
                @error('description')
                    {{ $message }}
                @enderror
            </div>

        </div>
        <button class="register-form__submit-button" type="submit">更 新</button>
    </form>
    @if(session('status'))
        <div class="register-form__submit-status">
            {{ session('status') }}
            </div>
    @endif
</div>
@endsection