<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CounterActivityLog as CounterActivityLogModel;
use Route;

class CounterActivityLog extends Component
{
    public $page_action = [];
    //public variables basic
    public $counter_activity_data = [];

    public $searchKey;
    public $data_count = 0;

    //open model delete
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
            $counter_activity = CounterActivityLogModel::find($this->delete_id);
            $counter_activity->delete();
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
        #if search active
        $this->counter_activity_data = DB::table('counter_activity_logs')
            ->select('counter_activity_logs.*', 'counters.counter as counter_name', 'users.name as users_name')
            ->join('properties', 'counter_activity_logs.property_id', 'properties.id')
            ->join('counters', 'counter_activity_logs.counter_id', 'counters.id')
            ->join('users', 'counter_activity_logs.auth_id', 'users.id')
            ->where('counter_activity_logs.property_id', '=', Auth::user()->property_id)
            ->latest()
            ->skip($this->count)
            ->limit(10)
            ->get();

        $this->data_count = DB::table('counter_activity_logs')
            ->count('id');
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

        return view('livewire.counter-activity-log')->layout('layouts.master');
    }
}
