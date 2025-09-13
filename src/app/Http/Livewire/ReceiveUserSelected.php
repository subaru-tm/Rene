<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ReceiveUserSelected extends Component
{
    public $selectedUser = '';

    protected $listeners = [
        'userSelect' => 'handleUserSelect',
    ];

    public function handleUserSelect($data)
    {
        $this->selectedUser = $data;
    }

    public function render()
    {
        return view('livewire.receive-user-selected');
    }
}
