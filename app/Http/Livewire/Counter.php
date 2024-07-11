<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Counter as CounterModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Route;

class Counter extends Component
{
    public $page_action = [];
    //public variables basic
    public $counter_data = [];

    public $searchKey;
    public $key = 0;
    public $showError = false;

    //new variable data management
    public $new_counter = "Counter ";

    public $data_count = 0;

    //open model insert
    public function openModel()
    {
        $this->clearData();
        $this->showError = false;
        $this->dispatchBrowserEvent('insert-show-form');
    }

    //close model insert
    public function closeModel()
    {
        $this->dispatchBrowserEvent('insert-hide-form');
    }

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
            $counter = CounterModel::find($this->delete_id);
            $counter->delete();
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
        if (!$this->searchKey) {
            $this->counter_data = DB::table('counters')
                ->select('counters.*')
                ->skip($this->count)
                ->limit(10)
                ->get();
        } else {
            $this->counter_data = DB::table('counters')
                ->select('counters.*')
                ->where('counters.counter', 'LIKE', '%' . $this->searchKey . '%')
                ->skip($this->count)
                ->limit(10)
                ->get();
        }

        $this->data_count = DB::table('counters')
            ->count('id');
    }

    // insert and update data here
    public function saveData()
    {
        $this->showError = true;
        //validate data
        $this->validate(
            [
                'new_counter' => 'required|max:255|unique:counters,counter'
            ]
        );

        //check id value and execute
        if ($this->key == 0) {
            //here insert data
            $data = new CounterModel();
            $data->counter = $this->new_counter;
            $data->property_id = Auth::user()->property_id;
            $data->save();

            //show success message
            session()->flash('message', 'Saved Successfully!');

            //clear data
            $this->clearData();
        } else {
            //here update data
            $data = CounterModel::find($this->key);
            $data->counter = $this->new_counter;
            $data->save();

            //show success message
            session()->flash('message', ' Update Successfuly!');

            //clear data
            $this->clearData();
        }
    }

    //fill box forupdate
    public function updateRecord($id)
    {
        $data = CounterModel::find($id);
        $this->new_counter = $data->counter;
        $this->key = $id;

        $this->showError = false;
        // open model in edit
        $this->dispatchBrowserEvent('insert-show-form');
    }

    public function updateCounterOpenStatus($counterId, $status)
    {
        $data = CounterModel::find($counterId);
        $data->open_status = $status;
        $data->save();

        session()->flash('status_message', 'Status Changed Successfully!');

    }

    public function updateCounterActiveStatus($counterId, $status)
    {
        $data = CounterModel::find($counterId);
        $data->active_status = $status;
        $data->save();

        session()->flash('status_message', 'Status Changed Successfully!');

    }

    //clear data
    public function clearData()
    {
        # emty field
        $this->key = 0;
        $this->new_counter = "Counter ";
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
        return view('livewire.counter')->layout('layouts.master');
    }
}
