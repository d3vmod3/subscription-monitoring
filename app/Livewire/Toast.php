<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 

class Toast extends Component
{
    public $show = false;
    public $message = '';
    public $type = null;
    public $duration = 3000;

    #[On('show-toast')]
    public function showToast($data)
    {
        $this->message = $data['message'];
        $this->type = $data['type'];
        $this->duration = $data['duration'];
        $this->show = true;
        // dd($this->duration);
        $this->dispatch('toast-hide', ['duration' => $this->duration]);
        
    }

    #[On('toast-hide')]
    public function hide(array $data)
    {
        sleep($this->duration / 1000);
        $duration = $data['duration'] ?? 3000;
        $this->dispatch('hide-toast-complete');
    }

    #[On('hide-toast-complete')]
    public function hideComplete()
    {
        $this->show = false;
        $this->message = '';
        
    }

    public function render()
    {
        return view('livewire.toast');
    }
}
