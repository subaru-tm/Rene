<div>
    <div class="header">
        <button class="menu-button" wire:click="openModal()" type="button">
            <img src="{{ asset('storage/menu_button.png') }}" alt="" />
        </button>
        <a class="app-name" href="/">
            Rese
        </a>
    </div>

    @if($showModal || $errors->any())
        <div class="modal-header">
            <button class="button-back--submit" wire:click="closeModal()" type="button">
                ×
            </button>
        </div>

        <div class="modal__background">
            <div class="modal">
                <div class="modal-body">
                    <div class="menu-item">
                        <a href="/" >
                            Home
                        </a>
                    </div>
                    @guest
                        <div class="menu-item">
                            <a href="/register" >
                                Registration
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="/login" >
                                Login
                            </a>
                        </div>
                    @else
                        <div class="menu-item" aria-labelledby="navbarDropdown">
                            <a href="/logout"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                    Logout
                            </a>
                            <form id="logout-form" action="/logout" method="POST">
                                @csrf
                            </form>
                        </div>
                        <div class="menu-item">
                            <a href="/mypage" >
                                Mypage
                            </a>
                        </div>
                        @if($user->is_admin)
                            <div class="menu-item">
                                <a href="/admin/index" >
                                    AdminPage
                                </a>
                            </div>
                        @endif
                        @if($user->is_manager)
                            <div class="menu-item">
                                <a href="/manager/index" >
                                    ManagerPage
                                </a>
                            </div>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    @endif

</div>
