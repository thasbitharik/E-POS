<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property as PropertyModel;
use Illuminate\Support\Facades\DB;
use Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use Livewire\WithFileUploads;

class Property extends Component
{
    use WithFileUploads;
    public $page_action = [];
    //public variables basic
    public $list_data = [];

    public $searchKey;
    public $key = 0;
    public $showError = false;
    public $message = "";

    public $view = [];
    public $view_id = 0;

    //new variable data management
    public $new_logo;
    public $new_name = "";
    public $new_mobile_no = "";
    public $new_email = "";
    public $new_address = "";
    public $new_landline_no = "";
    public $new_website = "";
    public $logo_url = "";

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

    public function openViewModel($id)
    {
        $this->view = PropertyModel::select('properties.*')
            ->where('properties.id', '=', $id)
            ->get();
        $this->dispatchBrowserEvent('view-show-form');
    }

    public function viewCloseModel()
    {
        $this->dispatchBrowserEvent('view-hide-form');
        $this->view = [];
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
            $property = PropertyModel::find($this->delete_id);

            if ($property->logo) {
                Storage::disk('property')->delete($property->logo);
            }

            $property->delete();
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
            $this->list_data = DB::table('properties')
                ->select('properties.*');
            if (Auth::user()->user_type_id != 1) {
                $this->list_data = $this->list_data->whereNot('properties.id', 1);
            }
            $this->list_data = $this->list_data->latest()
                ->skip($this->count)
                ->limit(10)
                ->get();
        } else {
            $this->list_data = DB::table('properties')
                ->select('properties.*');
            if (Auth::user()->user_type_id != 1) {
                $this->list_data = $this->list_data->whereNot('properties.id', 1);
            }
            $this->list_data = $this->list_data->where('properties.property_name', 'LIKE', '%' . $this->searchKey . '%')
                ->orWhere('properties.tp', 'LIKE', '%' . $this->searchKey . '%')
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
                'new_name' => 'required',
                'new_mobile_no' => 'required',
                'new_email' => 'email|regex:/(.*)\./i|',
                'new_logo' => 'nullable|image'
            ]
        );

        //check id value and execute
        if ($this->key == 0) {
            //here insert data
            $data = new PropertyModel();
            $data->property_name = $this->new_name;
            $data->tp = $this->new_mobile_no;
            $data->email = $this->new_email;
            $data->address = $this->new_address;
            $data->landline = $this->new_landline_no;
            $data->web = $this->new_website;
            $data->logo_url = $this->logo_url;

            if ($this->new_logo) {
                $filename = $this->storeImage();
                $data->logo = $filename;
            }

            $data->save();

            //show success message
            session()->flash('message', 'Saved Successfully!');

            //clear data
            $this->clearData();
        } else {
            //here update data
            $data = PropertyModel::find($this->key);
            $data->property_name = $this->new_name;
            $data->tp = $this->new_mobile_no;
            $data->email = $this->new_email;
            $data->address = $this->new_address;
            $data->landline = $this->new_landline_no;
            $data->web = $this->new_website;
            $data->logo_url = $this->logo_url;

            if ($this->new_logo) {
                $filename = $this->storeImage();

                if ($data->logo) {
                    Storage::disk('property')->delete($data->logo);
                }
                $data->logo = $filename;
            }

            $data->save();

            //show success message
            session()->flash('message', ' Update Successfuly!');

            //clear data
            $this->clearData();
        }
    }

    private function storeImage()
    {
        $extension = $this->new_logo->getClientOriginalExtension();
        $filename = $this->new_logo->getFilename();

        Storage::disk('property')->put($filename, file_get_contents($this->new_logo->getRealPath()));

        return $filename;
    }

    //fill box forupdate
    public function updateRecord($id)
    {
        $data = PropertyModel::find($id);
        $this->new_name = $data->property_name;
        $this->new_mobile_no = $data->tp;
        $this->new_email = $data->email;
        $this->new_address = $data->address;
        $this->new_landline_no = $data->landline;
        $this->new_website = $data->web;
        $this->logo_url = $data->logo_url;
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
        $this->new_name = "";
        $this->new_mobile_no = "";
        $this->new_email = "";
        $this->new_address = "";
        $this->new_landline_no = "";
        $this->new_website = "";
        $this->new_logo = null;
        $this->logo_url = "";
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
        return view('livewire.property')->layout('layouts.master');
    }
}
