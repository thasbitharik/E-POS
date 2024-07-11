<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Income as IncomeModel;
use App\Models\CashInHistory as CashInHistoryModel;
use Illuminate\Support\Facades\DB;
use Route;
use Illuminate\Support\Facades\Auth;

class Income extends Component
{
    public $page_action = [];
    //public variables basic
    public $income_types = [];

    public $property_list = [];

    public $filter_income_types = [];
    public $filter_properties = [];

    public $searchKey;
    public $key = 0;
    public $showError = false;
    public $message = "";

    public $propertyId;
    public $userId;
    public $select_income_type;
    public $select_property;
    //new variable data management
    public $new_income_type = "";
    public $new_property;
    public $new_income = "";
    public $new_amount = "";
    public $new_income_date = "";
    public $new_description = "";
    public $view = [];
    public $view_id = 0;

    public function mount()
    {
        $this->propertyId = Auth::user()->property_id;
        $this->userId = Auth::user()->id;
        $this->new_income_date = date('Y-m-d');
    }

    public function openViewModel($id)
    {
        $this->view = IncomeModel::select('incomes.*', 'income_types.income_type')
            ->leftJoin('income_types', 'incomes.income_type_id', 'income_types.id')
            ->where('incomes.id', '=', $id)
            ->get();

        $this->dispatchBrowserEvent('view-show-form');
    }

    public function viewCloseModel()
    {
        $this->dispatchBrowserEvent('view-hide-form');
        $this->view = [];
    }


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
            $income = IncomeModel::find($this->delete_id);
            $income->delete();
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

    // insert and update data here
    public function saveData()
    {
        $this->showError = true;

        $this->validate([
            'new_income_type' => 'required',
            'new_income' => 'required|max:255|',
            'new_amount' => 'required',
            'new_income_date' => 'required',
            'new_description' => 'max:255|',
            'new_property' => $this->propertyId == 1 ? 'required' : '',
        ]);

        //check id value and execute
        if ($this->key == 0) {
            //here insert data
            $data = new IncomeModel();
            if ($this->propertyId == 1) {
                $data->property_id = $this->new_property;
            } else {
                $data->property_id = $this->propertyId;
            }
            $data->user_id = $this->userId;
            $data->income_type_id = $this->new_income_type;
            $data->title = $this->new_income;
            $data->amount = $this->new_amount;
            $data->income_date = $this->new_income_date;
            $data->description = $this->new_description;

            $data->save();

            ///for income cash in
            $new_cash_in = new CashInHistoryModel();
            $new_cash_in->cash_in_type = "Income";
            $new_cash_in->amount = $this->new_amount;
            $new_cash_in->date = $this->new_income_date;
            $new_cash_in->property_id = Auth::user()->property_id;
            $new_cash_in->income_id = $data->id;
            $new_cash_in->save();

            //show success message
            session()->flash('message', 'Saved Successfully!');

            //clear data
            $this->clearData();
        } else {
            //here update data
            $data = IncomeModel::find($this->key);
            if ($this->propertyId == 1) {
                $data->property_id = $this->new_property;
            } else {
                $data->property_id = $this->propertyId;
            }
            $data->user_id = $this->userId;
            $data->income_type_id = $this->new_income_type;
            $data->title = $this->new_income;
            $data->amount = $this->new_amount;
            $data->income_date = $this->new_income_date;
            $data->description = $this->new_description;

            $data->save();

            //update cash in record
            $existing_cash_in_data = CashInHistoryModel::where('cash_in_histories.income_id', $this->key)->first();
            $existing_cash_in_data->amount = $this->new_amount;
            $existing_cash_in_data->date = $this->new_income_date;
            $existing_cash_in_data->save();

            //show success message
            session()->flash('message', ' Update Successfuly!');

            //clear data
            $this->clearData();
        }
    }

    //fill box forupdate
    public function updateRecord($id)
    {
        $data = IncomeModel::find($id);

        if ($this->propertyId == 1) {
            $this->new_property = $data->property_id;
        } else {
            $this->propertyId = $data->property_id;
        }

        $this->userId = $data->user_id;
        $this->new_income_type = $data->income_type_id;
        $this->new_income = $data->title;
        $this->new_amount = $data->amount;
        $this->new_income_date = $data->income_date;
        $this->new_description = $data->description;

        $this->key = $id;

        $this->showError = false;

        // open model in edit
        $this->dispatchBrowserEvent('insert-show-form');
    }

    //clear data
    public function clearData()
    {
        # empty field
        $this->key = 0;
        $this->new_income_type = "";
        $this->new_income = "";
        $this->new_amount = "";
        $this->new_income_date = date('Y-m-d');
        $this->new_description = "";

        if ($this->propertyId == 1) {
            $this->new_property = "";
        }
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
        $this->income_types = DB::table('income_types')
            ->select('income_types.*')
            ->get();

        $this->property_list = DB::table('properties')
            ->select('properties.*')
            ->where('id', '!=', 1)
            ->get();

        $this->filter_income_types = DB::table('income_types')->select('id', 'income_type')->get();

        $this->filter_properties = DB::table('properties')
            ->select('id', 'property_name')
            ->where('id', '!=', 1)
            ->get();

        $list_data = DB::table('incomes')
            ->select('incomes.*', 'income_types.income_type', 'properties.property_name')
            ->leftJoin('income_types', 'incomes.income_type_id', '=', 'income_types.id')
            ->leftJoin('properties', 'incomes.property_id', 'properties.id')
            ->where('incomes.title', 'LIKE', '%' . $this->searchKey . '%');
        if ($this->propertyId != 1) {
            $list_data = $list_data->where('incomes.property_id', $this->propertyId);
        }
        if ($this->select_income_type) {
            $list_data = $list_data->where('incomes.income_type_id', $this->select_income_type);
        }
        if ($this->select_property) {
            $list_data = $list_data->where('incomes.property_id', $this->select_property);
        }
        $list_data = $list_data->latest()
            ->skip($this->count)
            ->limit(10)
            ->get();

        $data_count = DB::table('incomes');
        if ($this->propertyId != 1) {
            $data_count = $data_count->where('incomes.property_id', $this->propertyId);
        }
        $data_count = $data_count->count('id');


        $filter_data_count = DB::table('incomes');
        if ($this->propertyId != 1) {
            $filter_data_count = $filter_data_count->where('incomes.property_id', $this->propertyId);
        }
        if ($this->select_income_type) {
            $filter_data_count = $filter_data_count->where('incomes.income_type_id', $this->select_income_type);
        }
        if ($this->select_property) {
            $filter_data_count = $filter_data_count->where('incomes.property_id', $this->select_property);
        }
        if ($this->select_income_type && $this->select_property) {
            $filter_data_count = $filter_data_count->where('incomes.income_type_id', $this->select_income_type)
                ->where('incomes.property_id', $this->select_property);
        }
        $filter_data_count = $filter_data_count->count('id');


        $this->pageAction();

        return view('livewire.income', compact('list_data', 'data_count', 'filter_data_count'))->layout('layouts.master');
    }
}
