<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Expence as ExpenceModel;
use App\Models\CashOutHistory as CashOutHistoryModel;
use App\Models\Company as CompanyModel;
use App\Models\Dealer as DealerModel;
use Illuminate\Support\Facades\DB;
use Route;
use Illuminate\Support\Facades\Auth;

class Expence extends Component
{
    public $page_action = [];
    //public variables basic
    public $expence_types = [];

    public $property_list = [];

    public $filter_expence_types = [];
    public $filter_properties = [];

    public $searchKey;
    public $key = 0;
    public $showError = false;
    public $message = "";

    public $propertyId;
    public $userId;
    public $select_expence_type;
    public $select_property;
    //new variable data management
    public $new_expence_type = "";
    public $new_bank_id = "";
    public $filter_company_id = "";
    public $new_dealer_id = "";
    public $new_staff_type = "";
    public $new_staff = "";
    public $new_salary_month = "";
    public $new_property;
    public $new_expence = "";
    public $new_amount = "";
    public $new_date = "";
    public $new_description = "";

    public $userTypes = [];
    public $users = [];
    public $banks = [];
    public $companies = [];
    public $dealers = [];

    public $view = [];
    public $view_id = 0;

    public $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    public function mount()
    {
        $this->propertyId = Auth::user()->property_id;
        $this->userId = Auth::user()->id;
        $this->new_date = date('Y-m-d');
    }

    public function openViewModel($id)
    {
        $this->view = ExpenceModel::select(
            'expences.*',
            'expence_types.expence_type',
            'user_types.user_type as staff_type',
            'users.name as staff_name',
            'banks.bank',
            'banks.account_number',
            'dealers.name as dealer_name',
            'companies.company_name'
        )
            ->leftJoin('expence_types', 'expences.expence_type_id', 'expence_types.id')
            ->leftJoin('banks', 'expences.bank_id', 'banks.id')
            ->leftJoin('dealers', 'expences.dealer_id', 'dealers.id')
            ->leftJoin('companies', 'dealers.company_id', 'companies.id')
            ->leftJoin('user_types', 'expences.staff_type_id', 'user_types.id')
            ->leftJoin('users', 'expences.staff_id', 'users.id')
            ->where('expences.id', '=', $id)
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
            $expence = ExpenceModel::find($this->delete_id);
            $expence->delete();
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

        // if ($this->propertyId == 1) {
        //     $this->validate(
        //         [
        //             'new_property' => 'required'
        //         ]
        //     );
        // }

        // if ($this->new_expence_type == 5) {
        //     $this->validate(
        //         [
        //             'new_staff_type' => 'required',
        //             'new_staff' => 'required'
        //         ]
        //     );
        // }

        // //validate data
        // $this->validate(
        //     [
        //         'new_expence_type' => 'required',
        //         'new_expence' => 'required|max:255|',
        //         'new_amount' => 'required',
        //         'new_description' => 'max:255|'
        //     ]
        // );

        $this->validate([
            'new_expence_type' => 'required',
            'new_expence' => 'required|max:255',
            'new_amount' => 'required',
            'new_description' => 'max:255',
            'new_property' => $this->propertyId == 1 ? 'required' : '',
            'new_bank_id' => $this->new_expence_type == 31 ? 'required' : '',
            'new_dealer_id' => $this->new_expence_type == 32 ? 'required' : '',
            'new_staff_type' => $this->new_expence_type == 25 ? 'required' : '',
            'new_staff' => $this->new_expence_type == 25 ? 'required' : '',
            'new_salary_month' => $this->new_expence_type == 25 ? 'required' : ''
        ]);

        //check id value and execute
        if ($this->key == 0) {
            //here insert data
            $data = new ExpenceModel();
            if ($this->propertyId == 1) {
                $data->property_id = $this->new_property;
            } else {
                $data->property_id = $this->propertyId;
            }
            $data->user_id = $this->userId;
            $data->expence_type_id = $this->new_expence_type;
            $data->title = $this->new_expence;
            $data->amount = $this->new_amount;
            $data->expence_date = $this->new_date;
            $data->description = $this->new_description;

            if ($this->new_expence_type == 25) {
                $data->staff_type_id = $this->new_staff_type;
                $data->staff_id = $this->new_staff;
                $data->salary_month = $this->new_salary_month;
            }

            if ($this->new_expence_type == 31) {
                $data->bank_id = $this->new_bank_id;
            }

            if ($this->new_expence_type == 32) {
                $data->dealer_id = $this->new_dealer_id;
            }

            $data->save();

            ///for expence cash out
            $new_cash_out = new CashOutHistoryModel();
            $new_cash_out->cash_out_type = "Expence";
            $new_cash_out->amount = $this->new_amount;
            $new_cash_out->date = $this->new_date;
            $new_cash_out->property_id = Auth::user()->property_id;
            $new_cash_out->expence_id = $data->id;
            $new_cash_out->save();
            ///////////

            //show success message
            session()->flash('message', 'Saved Successfully!');

            //clear data
            $this->clearData();

        } else {

            //here update data
            $data = ExpenceModel::find($this->key);
            if ($this->propertyId == 1) {
                $data->property_id = $this->new_property;
            } else {
                $data->property_id = $this->propertyId;
            }

            $data->user_id = $this->userId;
            $data->expence_type_id = $this->new_expence_type;
            $data->title = $this->new_expence;
            $data->amount = $this->new_amount;
            $data->expence_date = $this->new_date;
            $data->description = $this->new_description;

            if ($this->new_expence_type == 25) {
                $data->staff_type_id = $this->new_staff_type;
                $data->staff_id = $this->new_staff;
                $data->salary_month = $this->new_salary_month;
            }

            if ($this->new_expence_type == 31) {
                $data->bank_id = $this->new_bank_id;
            }

            if ($this->new_expence_type == 32) {
                $data->dealer_id = $this->new_dealer_id;
            }

            $data->save();

            //update cash out record
            $existing_cash_out_data = CashOutHistoryModel::where('cash_out_histories.expence_id', $this->key)->first();
            $existing_cash_out_data->amount = $this->new_amount;
            $existing_cash_out_data->date = $this->new_date;
            $existing_cash_out_data->save();

            //show success message
            session()->flash('message', ' Update Successfuly!');

            //clear data
            $this->clearData();
        }
    }

    //fill box forupdate
    public function updateRecord($id)
    {
        $data = ExpenceModel::find($id);

        if ($this->propertyId == 1) {
            $this->new_property = $data->property_id;
        } else {
            $this->propertyId = $data->property_id;
        }

        $this->userId = $data->user_id;
        $this->new_expence_type = $data->expence_type_id;
        $this->new_expence = $data->title;
        $this->new_amount = $data->amount;
        $this->new_date = $data->expence_date;
        $this->new_description = $data->description;

        if ($this->new_expence_type == 25) {
            $this->new_staff_type = $data->staff_type_id;
            $this->new_staff = $data->staff_id;
            $this->new_salary_month = $data->salary_month;
        }

        if ($this->new_expence_type == 31) {
            $this->new_bank_id = $data->bank_id;
        }

        if ($this->new_expence_type == 32) {
            $this->new_dealer_id = $data->dealer_id;
        }

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
        $this->new_expence_type = "";
        $this->new_expence = "";
        $this->new_amount = "";
        $this->new_date = date('Y-m-d');
        $this->new_description = "";

        $this->new_property = "";

        $this->new_staff_type = "";
        $this->new_staff = "";
        $this->new_salary_month = "";

        $this->new_bank_id = "";

        $this->filter_company_id = "";
        $this->new_dealer_id = "";
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
        $this->userTypes = DB::table('user_types')
            ->select('user_types.*')
            ->whereNotIn('user_types.id', [1])
            ->get();

        $this->users = DB::table('users')
            ->select('users.*')
            ->whereNotIn('users.user_type_id', [1])
            ->where('users.user_type_id', '=', $this->new_staff_type)
            ->get();

        $this->expence_types = DB::table('expence_types')
            ->select('expence_types.*')
            ->get();

        $this->banks = DB::table('banks')
            ->select('banks.*')
            ->get();

        $this->companies = CompanyModel::select('companies.*')->orderBy('companies.company_name', 'ASC')->get();
        $this->dealers = DealerModel::select('dealers.*');
        if ($this->filter_company_id) {
            $this->dealers = $this->dealers->where('dealers.company_id', '=', $this->filter_company_id);
        }
        $this->dealers = $this->dealers->orderBy('dealers.name', 'ASC')
            ->get();

        $this->property_list = DB::table('properties')
            ->select('properties.*')
            ->where('id', '!=', 1)
            ->get();

        $this->filter_expence_types = DB::table('expence_types')->select('id', 'expence_type')->get();
        $this->filter_properties = DB::table('properties')
            ->select('id', 'property_name')
            ->where('id', '!=', 1)
            ->get();

        $list_data = DB::table('expences')
            ->select('expences.*', 'expence_types.expence_type', 'properties.property_name')
            ->leftJoin('expence_types', 'expences.expence_type_id', '=', 'expence_types.id')
            ->leftJoin('properties', 'expences.property_id', 'properties.id')
            ->where('expences.title', 'LIKE', '%' . $this->searchKey . '%');
        if ($this->propertyId != 1) {
            $list_data = $list_data->where('expences.property_id', $this->propertyId);
        }
        if ($this->select_expence_type) {
            $list_data = $list_data->where('expences.expence_type_id', $this->select_expence_type);
        }
        if ($this->select_property) {
            $list_data = $list_data->where('expences.property_id', $this->select_property);
        }
        $list_data = $list_data->latest()
            ->skip($this->count)
            ->limit(10)
            ->get();

        $data_count = DB::table('expences');
        if ($this->propertyId != 1) {
            $data_count = $data_count->where('expences.property_id', $this->propertyId);
        }
        $data_count = $data_count->count('id');


        $filter_data_count = DB::table('expences');
        if ($this->propertyId != 1) {
            $filter_data_count = $filter_data_count->where('expences.property_id', $this->propertyId);
        }
        if ($this->select_expence_type) {
            $filter_data_count = $filter_data_count->where('expences.expence_type_id', $this->select_expence_type);
        }
        if ($this->select_property) {
            $filter_data_count = $filter_data_count->where('expences.property_id', $this->select_property);
        }
        if ($this->select_expence_type && $this->select_property) {
            $filter_data_count = $filter_data_count->where('expences.expence_type_id', $this->select_expence_type)
                ->where('expences.property_id', $this->select_property);
        }
        $filter_data_count = $filter_data_count->count('id');


        $this->pageAction();

        return view('livewire.expence', compact('list_data', 'data_count', 'filter_data_count'))->layout('layouts.master');
    }
}