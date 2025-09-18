<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class ChoiceManagerModal extends Component
{
    public $showModal=false;
    public $old_user;  // 更新の場合の登録済の代表者
    public $users; // 新規・更新に関わらずモーダル一覧に表示する代表者

    public function mount() {
        $this->users = User::where('is_manager', true)->get();
    }

    public function openModal() {
        $this->showModal=true;
    }

    public function closeModal() {
        $this->showModal=false;
        $this->selectedUser=null;
    }

    public function render()
    {
        return view('livewire.choice-manager-modal');
    }

   public function selectUser(User $user) {
        $this->emitUp('userSelect', $user);
        $this->closeModal();
    }
}
