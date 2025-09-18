<div class="form-item__select-manager">
    @if( !empty($user) )
        <!-- 店舗更新画面からの呼び出しで代表者が登録済の場合
          -- 変更できるようにボタンは表示。モーダルを開かなくても値は保持。 -->

        <?php  
            $user_id = $user->id;
            $name = $user->name;
        ?>

        @livewire('choice-manager-modal', ['old_user' => $user])


    @else
        @livewire('choice-manager-modal')
    @endif

    <!-- 店舗更新画面で既に代表者登録済であってもモーダルで新たに選択すると上書きされる -->
    <?php if(!empty($selectedUser)) { 
        $user_id = $selectedUser['id'];
        $name = $selectedUser['name'];
    } ?>

    <span class="selected-user_display">
        @if(isset($name))
            {{ $name }}
            <input type="hidden" name="user_id" value="{{ $user_id }}" />
        @else
            選択されていません
        @endif

    </span>


</div>
