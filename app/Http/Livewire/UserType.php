<?php

namespace App\Http\Livewire;

use App\Models\UserType as UserTypeModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Route;

class UserType extends Component
{
    public $page_action = [];
    //public variables basic
    public $list_data = [];
    public $searchKey;
    public $key = 0;
    public $showError = false;
    public $message = "";
    public $display = "none";

    //new variable data management
    public $new_user_type = "";
    public $default_root = "sales-view";

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
        # code...
        if ($this->delete_id != 0) {
            $access_model = UserTypeModel::find($this->delete_id);
            $access_model->delete();
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
            $this->list_data = DB::table('user_types')
                ->select('user_types.*')
                ->latest()
                ->skip($this->count)
                ->limit(10)
                ->get();
        } else {
            $this->list_data = DB::table('user_types')
                ->select('user_types.*')
                ->where('user_types.user_type', 'LIKE', '%' . $this->searchKey . '%')
                ->latest()
                ->skip($this->count)
                ->limit(10)
                ->get();
        }
    }

    // insert and update data here
    public function saveData()
    {
        $this->showError = true;
        //validate data


        //check id value and execute
        if ($this->key == 0) {
            $this->validate(
                [
                    'new_user_type' => 'required|max:255|unique:user_types,user_type'

                ]
            );

            //here insert data
            $data = new UserTypeModel();
            $data->user_type = $this->new_user_type;
            $data->default_root = $this->default_root;
            $data->save();

            //show success message
            session()->flash('message', ' Save Successfully!');

            //clear data
            $this->clearData();
        } else {
            //here update data
            $data = UserTypeModel::find($this->key);
            $this->validate(
                [
                    'new_user_type' => 'required|max:255|unique:user_types,user_type,'.$this->key

                ]
            );
            $data->user_type = $this->new_user_type;
            $data->default_root = $this->default_root;
            $data->save();

            //show success message
            session()->flash('message', 'Update Successfully!');

            //clear data
            $this->clearData();
        }
    }

    //fill box forupdate
    public function updateRecord($id)
    {
        $data = UserTypeModel::find($id);
        $this->new_user_type = $data->user_type;
       $this->default_root=   $data->default_root;
        $this->key = $id;

        $this->showError = false;
        // open model in edit
        $this->dispatchBrowserEvent('insert-show-form');
    }

    //clear data
    public function clearData()
    {
        # emty field
        $this->key = 0;
        $this->new_user_type = "";
        $this->default_root = "sales-view";
    }

    #comon controls
    public function pageAction()
    {
        # code...
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
        //display records
        $this->fetchData();
        $this->pageAction();
        return view('livewire.user-type')->layout('layouts.master');
    }
}
