@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-index.css') }}" >
@endsection

@section('content')
<div class="content">
    <div class="search">
        <form class="search-form" action="/admin/index/search" method="GET">
            @csrf
            <span class="search-form__input">
                <img src="{{ asset('storage/search_img.png') }}" alt="" />
                <input type="text" name="email_keyword" placeholder="Search email" 
                    @if( isset($email_keyword) )
                        value="{{ $email_keyword }}"
                    @endif
                />
            </span>
            <p class="search-form__separator">|</p>
            <span class="search-form__input">
                <img src="{{ asset('storage/search_img.png') }}" alt="" />
                <input type="text" name="name_keyword" placeholder="Search name" 
                    @if( isset($name_keyword) )
                        value="{{ $name_keyword }}"
                    @endif
                />
            </span>
            <button type="submit" style="display: none"></button>
        </form>
    </div>
    <div class="content-header">
        <h2 class="content-header__name">{{ $user->name }}さん　管理者</h2>
        <a class="content-header__notify-link" href="/admin/notify">利用者へのお知らせ</a>
    </div>
    <div class="list-table">
        <h3>店舗代表者登録</h3>
        <table class="list-table__inner">
            <tr class="list-table__row">
                <th class="list-table__header">
                    <td class="list-table__header-item">ID</td>
                    <td class="list-table__header-item">メールアドレス</td>
                    <td class="list-table__header-item">名前</td>
                    <td class="list-table__header-item">店舗代表者</td>
                    <td class="list-table__header-item"></td>
                </th>
            </tr>
            @foreach( $list_users as $user )
                <tr class="list-table__row">
                    <th></th>
                    <td class="list-table__item-center">{{ $user->id }}</td>
                    <td class="list-table__item-left">{{ $user->email }}</td>
                    <td class="list-table__item-left">{{ $user->name }}</td>
                    <td class="list-table__item-center">
                        @if($user->is_manager)
                            Yes
                        @else
                            No
                        @endif
                    </td>
                    <td class="list-table__item-center">
                        @if ($user->is_manager)
                            <!-- 該当行のユーザーが既に店舗代表者の場合、権限解除のボタン -->
                            <form class="table-item__form-revoke" action="/admin/revoke/{{ $user->id }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit">代表者権限を解除</button>
                            </form>
                        @else
                            <!-- 該当行のユーザーに店舗代表者の権限がない場合、権限付与のボタン -->
                            <form class="table-item__form-empowerment" action="/admin/empowerment/{{ $user->id }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit">代表者権限を付与</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection