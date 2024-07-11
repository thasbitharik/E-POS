<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Dashboard extends Component
{

    public $new_name;
    
    public function oprnModel()
    {
        # code...
        $this->dispatchBrowserEvent('show-form');
    }
    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.master');
    }
}
