<?php

namespace App\Http\Livewire;

use App\Models\User as UserModel;
use App\Models\UserType as UserTypeModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Route;

class Users extends Component
{
    public $page_action = [];
    //public variables basic
    public $list_data = [];
    public $user_type_list = [];

    public $property_list = [];
    public $searchKey;
    public $key = 0;
    public $showError = false;
    public $message = "";
    public $display = "none";

    //new variable data management
    public $user_id = 0;

    public $new_user_name;
    public $new_user_tp;
    public $new_user_email;
    public $new_confirm_password;
    public $new_user_password;
    public $new_user_type;

    public $new_property_id;

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
            $user_data = UserModel::find($this->delete_id);
            $user_data->delete();
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
        $this->property_list = DB::table('properties')
        ->select('properties.*')
        ->get();

        $this->user_type_list = UserTypeModel::all();

        #if search active
        if (!$this->searchKey) {
            $this->list_data = DB::table('users')
                ->select('users.*', 'user_types.user_type','properties.property_name')
                ->leftJoin('properties', 'users.property_id','properties.id')
                ->leftJoin('user_types', 'users.user_type_id', '=', 'user_types.id')
                ->latest()
                ->skip($this->count)
                ->limit(10)
                ->get();
        } else {
            $this->list_data = DB::table('users')
                ->select('users.*', 'user_types.user_type','properties.property_name')
                ->leftJoin('properties', 'users.property_id','properties.id')
                ->leftJoin('user_types', 'users.user_type_id', '=', 'user_types.id')
                ->where('users.name', 'LIKE', '%' . $this->searchKey . '%')
                ->orWhere('users.tp', 'LIKE', '%' . $this->searchKey . '%')
                ->orWhere('users.email', 'LIKE', '%' . $this->searchKey . '%')
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
        $this->validate(
            [
                'new_user_name' => 'required|max:255',
                'new_user_tp' => 'required|max:12',
                'new_user_email' => 'required|email|max:45|unique:users,email,'.$this->key,
                'new_user_type' => 'required',
                'new_property_id' => 'required',
                'new_user_password' => 'required|min:6',
                'new_confirm_password' => 'required_with:new_user_password|same:new_user_password|min:6'

            ]
        );

        //unique:users|
        if ($this->key == 0) {
            //create new record here
            $data = new UserModel();
            $data->name = $this->new_user_name;
            $data->tp = $this->new_user_tp;
            $data->email = $this->new_user_email;
            $data->user_type_id = $this->new_user_type;
            $data->property_id = $this->new_property_id;
            $data->password = Hash::make($this->new_user_password);
            $data->save();
            session()->flash('message', 'Saved successfully!.');
        } else {
            //update
            $data = UserModel::find($this->key);
            $data->name = $this->new_user_name;
            $data->tp = $this->new_user_tp;
            $data->email = $this->new_user_email;
            $data->user_type_id = $this->new_user_type;
            $data->property_id = $this->new_property_id;
            $data->password = Hash::make($this->new_user_password);
            $data->save();
            session()->flash('message', 'Update successfully!');
        }
        //clear data
        $this->clearData();
    }


    //fill box forupdate
    public function updateRecord($id)
    {

        $data = UserModel::find($id);
        $this->new_user_name = $data->name;
        $this->new_user_tp = $data->tp;
        $this->new_user_email = $data->email;
        $this->new_user_type = $data->user_type_id;
        $this->new_property_id = $data->property_id;
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
        $this->new_property_id = "";
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
        return view('livewire.users')->layout('layouts.master');
    }
}
