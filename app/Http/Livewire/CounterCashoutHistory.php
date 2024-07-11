<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\CounterCashOutHistory as CounterCashOutHistoryModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Route;

class CounterCashoutHistory extends Component
{
    public $page_action = [];
    public $counter_cashout_data = [];

    public $cashier_data = [];
    public $counter_data = [];

    public $select_cashier = 0;
    public $select_counter = 0;
    public $select_date;

    public $searchKey;
    public $data_count = 0;
    public $delete_id = 0;

    public function deleteOpenModel($id)
    {
        $this->delete_id = $id;
        $this->dispatchBrowserEvent('delete-show-form');
    }

    #delete Data
    public function deleteRecord()
    {
        if ($this->delete_id != 0) {
            $counter_cashout = CounterCashOutHistoryModel::find($this->delete_id);
            $counter_cashout->delete();
            $this->deleteCloseModel();

            //show Delete message
            session()->flash('del_message', 'Record Deleted!');
        }
    }

    //close model close
    public function deleteCloseModel()
    {
        $this->dispatchBrowserEvent('delete-hide-form');
    }

    // pagination purpose code
    public $count = 0;
    public function add()
    {
        $this->count = $this->count + 10;
    }

    public function les()
    {
        $this->count = $this->count - 10;
    }

    //fetch data from db
    public function fetchData()
    {
        $this->cashier_data = DB::table('users')
            ->select('users.id', 'users.name as username')
            ->where('users.user_type_id', '=', 5)
            ->where('users.property_id', '=', Auth::user()->property_id)
            ->get();

        $this->counter_data = DB::table('counters')
            ->select('counters.id', 'counters.counter as counter_name')
            ->where('counters.property_id', '=', Auth::user()->property_id)
            ->get();

        #if search active
        $this->counter_cashout_data = DB::table('counter_cash_out_histories')
            ->select('counter_cash_out_histories.*', 'counters.counter as counter_name', 'users.name as users_name')
            ->join('properties', 'counter_cash_out_histories.property_id', 'properties.id')
            ->join('counters', 'counter_cash_out_histories.counter_id', 'counters.id')
            ->join('users', 'counter_cash_out_histories.user_id', 'users.id')
            ->where('counter_cash_out_histories.property_id', '=', Auth::user()->property_id);

        if ($this->select_cashier) {
            $this->counter_cashout_data = $this->counter_cashout_data->where('counter_cash_out_histories.user_id', '=', $this->select_cashier);
        }

        if ($this->select_counter) {
            $this->counter_cashout_data = $this->counter_cashout_data->where('counter_cash_out_histories.counter_id', '=', $this->select_counter);
        }

        if ($this->select_date) {
            $this->counter_cashout_data = $this->counter_cashout_data->where('counter_cash_out_histories.date', '=', $this->select_date);
        }

        $this->counter_cashout_data = $this->counter_cashout_data->latest()
            ->skip($this->count)
            ->limit(10)
            ->get();

        $this->data_count = DB::table('counter_cash_out_histories')
            ->count('id');
    }

    public function clearFilter()
    {
        $this->select_counter = 0;
        $this->select_cashier = 0;
        $this->select_date = null;
    }

    #comon controls
    public function pageAction()
    {
        $x = Route::currentRouteName();
        $data = DB::table('access_points')
            ->select('access_points.access_model_id', 'access_points.id as access_point', 'access_points.value', 'access_models.access_model')
            ->join('access_models', 'access_points.access_model_id', '=', 'access_models.id')
            ->where('access_models.access_model', '=', $x)
            ->get();

        $access = session()->get('Access');
        for ($i = 0; $i < sizeof($data); $i++) {
            if (in_array($data[$i]->access_point, $access)) {
                array_push($this->page_action, $data[$i]->value);
            }
        }
    }

    public function render()
    {
        $this->fetchData();
        $this->pageAction();

        return view('livewire.counter-cashout-history')->layout('layouts.master');
    }
}
