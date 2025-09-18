<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ReviewModal extends Component
{
    public $reservation_id;

    public $showModal=false;

    public function openModal() {
        $this->showModal = true;
    }

    public function closeModal() {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.review-modal');
    }
}
