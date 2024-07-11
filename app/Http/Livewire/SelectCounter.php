<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Counter as CounterModel;
use Illuminate\Support\Facades\Auth;

class SelectCounter extends Component
{
    public $select_counter;
    public $counters = [];

    public function mount()
    {
        if (session()->get('counter_id')) {
            $this->select_counter = session()->get('counter_id');
        }
    }

    public function goBack()
    {
        return redirect('/');
    }

    public function gotoSale()
    {
        $this->validate(
            [
                'select_counter' => 'required'
            ],
            [
                'select_counter.required' => 'Please select a counter'
            ]
        );
        if ($this->select_counter) {
            $counter = CounterModel::find($this->select_counter);
            $counter->active_status = 1;
            $counter->save();

            session()->put('counter_id', $this->select_counter);
            return redirect('/sales-view');
        }
    }

    public function render()
    {
        $this->counters = DB::table('counters')
            ->select('counters.*')
            // ->where('counters.active_status', 0)
            ->where('counters.property_id', Auth::user()->property_id)
            ->get();

        return view('livewire.select-counter')->layout('layouts.select_counter_layout');
    }
}
