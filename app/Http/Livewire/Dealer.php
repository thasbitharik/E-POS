<?php
namespace App\Http\Livewire;

use App\Models\Company as CompanyModel;
use App\Models\Dealer as DealerModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Route;

class Dealer extends Component
{
    public $page_action = [];
    //public variables basic
    public $list_data = [];
    public $companies = [];

    public $searchKey;
    public $key = 0;
    public $showError = false;
    public $message = "";


    //new variable data management
    public $new_name;
    public $new_contact;
    public $new_email;
    public $new_company;


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
        # code...
        if ($this->delete_id != 0) {
            $delear = DealerModel::find($this->delete_id);
            $delear->delete();
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
        $this->companies = CompanyModel::select('companies.*')->orderBy('companies.company_name', 'ASC')->get();

        #if search active
        if (!$this->searchKey) {
            $this->list_data = DB::table('dealers')
                ->select('dealers.*', 'companies.company_name')
                ->join('companies', 'dealers.company_id', '=', 'companies.id')
                ->latest()
                ->skip($this->count)
                ->limit(10)
                ->get();
        } else {
            $this->list_data = DB::table('dealers')
                ->select('dealers.*', 'companies.company_name')
                ->join('companies', 'dealers.company_id', '=', 'companies.id')
                ->where('dealers.name', 'LIKE', "%{$this->searchKey}%")
                ->orWhere('dealers.tp', 'LIKE', "%{$this->searchKey}%")
                ->orWhere('dealers.email', 'LIKE', "%{$this->searchKey}%")
                ->latest()
                ->skip($this->count)
                ->limit(10)
                ->get();
        }

        $this->data_count = DB::table('dealers')
        ->count('id');
    }

    // insert and update data here
    public function saveData()
    {
        $this->showError = true;

        //validate data
        $this->validate(
            [
                'new_company' => 'required',
                'new_name' => 'required|max:255',
                'new_contact' => 'required|max:255'
            ]
        );

        //check id value and execute
        if ($this->key == 0) {
            //here insert data
            $data = new DealerModel();
            $data->company_id = $this->new_company;
            $data->name = $this->new_name;
            $data->tp = $this->new_contact;
            $data->email = $this->new_email;
            $data->save();

            //show success message
            session()->flash('message', 'Saved Successfully!');

            //clear data
            $this->clearData();
        } else {
            //here update data
            $data = DealerModel::find($this->key);
            $data->company_id = $this->new_company;
            $data->name = $this->new_name;
            $data->tp = $this->new_contact;
            $data->email = $this->new_email;
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
        $data = DealerModel::find($id);
        $this->new_name = $data->name;
        $this->new_contact = $data->tp;
        $this->new_email = $data->email;
        $this->new_company = $data->company_id;
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
        $this->new_contact = "";
        $this->new_company = "";
        $this->new_email = "";
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
        $this->fetchData();
        $this->pageAction();
        return view('livewire.dealer')->layout('layouts.master');
    }
}