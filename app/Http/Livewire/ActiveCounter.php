<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Route;

class ActiveCounter extends Component
{
    public $active_counter;
    public $counter_id;

    public function mount()
    {
        if (session('counter_id')) {
            $this->counter_id = session()->get('counter_id');
        } else {
            $this->counter_id = null;
        }
    }

    public function render()
    {
        if ($this->counter_id) {
            $this->active_counter = DB::table('counters')
                ->select('counters.counter as counter_name')
                ->where('counters.id', '=', $this->counter_id)
                ->value('counter_name');
        } else {
            $this->active_counter = null;
        }

        return view('livewire.active-counter');
    }
}
