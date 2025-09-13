<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Modal extends Component
{
    public $showModal=false;


    public function openModal(){
        $this->showModal=true;
    }

    public function closeModal(){
        $this->showModal=false;
    }

    public function render()
    {
        $user = auth()->user();

        return view('livewire.modal')->with('user', $user);
    }
}
