<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class ChoiceManagerModal extends Component
{
    public $showModal=false;
    public $users;

    public function mount() {
        $this->users = User::where('is_manager', true)->get();
    }

    public function openModal() {
        $this->showModal=true;
    }

    public function closeModal() {
        $this->showModal=false;
        $this->selectedUser = null;
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
