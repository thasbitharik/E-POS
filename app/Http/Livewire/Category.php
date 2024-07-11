<?php
namespace App\Http\Livewire;

use App\Models\Category as CategoryModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Route;

class Category extends Component
{
    public $page_action = [];
    //public variables basic
    public $list_data = [];

    public $searchKey;
    public $key = 0;
    public $showError = false;
    public $message = "";


    //new variable data management
    public $new_category = "";

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
            $category = CategoryModel::find($this->delete_id);
            $category->delete();
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
    public $count=0;
    public function add()
    {
        $this->count=$this->count+10;
    }

    public function les()
    {
        $this->count=$this->count-10;
    }

    //fetch data from db
    public function fetchData()
    {
        #if search active
        if (!$this->searchKey) {
            $this->list_data = DB::table('categories')
                ->select('categories.*')
                ->latest()
                ->skip($this->count)
                ->limit(10)
                ->get();
        } else {
            $this->list_data = DB::table('categories')
                ->select('categories.*')
                ->where('categories.category', 'LIKE', '%' . $this->searchKey . '%')
                ->latest()
                ->skip($this->count)
                ->limit(10)
                ->get();
        }

        $this->data_count = DB::table('categories')
        ->count('id');
    }

    // insert and update data here
    public function saveData()
    {
        $this->showError = true;
        //validate data
        $this->validate(
            [
                'new_category' => 'required|max:255|unique:categories,category'

            ]
        );

        //check id value and execute
        if ($this->key == 0) {
            //here insert data
            $data = new CategoryModel();
            $data->category = $this->new_category;
            $data->save();

            //show success message
            session()->flash('message', 'Saved Successfully!');

            //clear data
            $this->clearData();
        } else {
            //here update data
            $data = CategoryModel::find($this->key);
            $data->category = $this->new_category;
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
        $data = CategoryModel::find($id);
        $this->new_category = $data->category;
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
        $this->new_category = "";
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
        return view('livewire.category')->layout('layouts.master');
    }
}