<div>
    <div class="content-header__modal-open">
        <button class="choice-manager__button" wire:click="openModal()" type="button">
            @if( !empty($old_user) )
                店舗代表者を変更
            @else
                店舗代表者を選択
            @endif
        </button>
    </div>
    @if($showModal)
        <div class="select-modal__background">
            <div class="user-search">
                <button type="button" wire:click="closeModal()">×</button>
                <form class="user-search__form" action="/admin/index/search" method="GET">
                    @csrf
                    <span class="user-search__form-input">
                    <img src="{{ asset('storage/search_img.png') }}" alt="" />
                    <input type="text" name="email_keyword" placeholder="Search email" 
                        @if( isset($email_keyword) )
                            value="{{ $email_keyword }}"
                        @endif
                    />
                    </span>
                    <p class="user-search__form-separator">|</p>
                    <span class="user-search__form-input">
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
            <div class="select-modal__header">
                <p>店舗代表者を選択</p>
            </div>
            <div class="select-modal__body">
                <table class="list-table__inner">
                    <tr class="list-table__row">
                        <th class="list-table__header">
                            <td class="list-table__header-item">メールアドレス</td>
                            <td class="list-table__header-item">名前</td>
                            <td class="list-table__header-item"></td>
                        </th>
                    </tr>
                    @foreach( $users as $user )
                        <tr class="list-table__row">
                            <th></th>
                            <td class="list-table__item">{{ $user->email }}</td>
                            <td class="list-table__item">{{ $user->name }}</td>
                            <td class="list-table__item">
                                <button type="button" wire:click="selectUser({{ $user }})">選択する</button>
                            </td>
                @endforeach
            </div>
        </div>
    @endif

</div>
