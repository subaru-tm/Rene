<div class="form-item__select-manager">
    @livewire('choice-manager-modal')

    <?php if(!empty($selectedUser)) { 
        $user_id = $selectedUser['id'];
        $name = $selectedUser['name'];
        $email = $selectedUser['email'];        
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
