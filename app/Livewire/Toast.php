<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 

class Toast extends Component
{
    public $show = false;
    public $message = '';
    public $type = 'success';
    public $duration = 3000;

    #[On('show-toast')]
    public function showToast($data)
    {
        $this->message = $data['message'];
        $this->type = $data['type'];
        $this->duration = $data['duration'];
        $this->show = true;
    }

    public function hide()
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.toast');
    }
}
