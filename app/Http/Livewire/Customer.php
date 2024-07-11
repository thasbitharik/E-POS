<?php

namespace App\Http\Livewire;

use App\Models\Customer as CustomerModel;
use App\Models\CashInHistory as CashInHistoryModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Route;
use Auth;


// use Carbon\Carbon;
// use DNS1D;
class Customer extends Component
{
    public $page_action = [];
    //public variables basic
    public $list_data = [];

    public $searchKey;
    public $key = 0;
    public $showError = false;
    public $message = "";

    //new variable data management
    public $new_name = "";
    public $new_contact;

    public $data_count = 0;

    public $new_credit_limit = 0.00;
    // public $new_credit_spent = 0.00;
    public $new_received_credit = 0.00;
    public $new_credit_details = 0;

    public $show_credit_detail = 0;
    public $customer_credit = 0.00;
    public $payable_credit = 0.00;
    public $paid_credit = 0.00;

    public $purchase_amount = 0;




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
            $customer = CustomerModel::find($this->delete_id);
            $customer->delete();
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
            $this->list_data = DB::table('customers')
                ->select('customers.*')
                ->latest()
                ->skip($this->count)
                ->limit(10)
                ->get();
        } else {
            $this->list_data = DB::table('customers')
                ->select('customers.*')
                ->where('customers.customer_name', 'LIKE', '%' . $this->searchKey . '%')
                ->orWhere('customers.tp', 'LIKE', '%' . $this->searchKey . '%')
                ->latest()
                ->skip($this->count)
                ->limit(10)
                ->get();
        }

        $this->data_count = DB::table('customers')
            ->count('id');
    }


    // insert and update data here
    public function saveData()
    {
        $this->showError = true;

        // Check id value and execute
        if ($this->key == 0) {
            // validate data for insert
            $this->validate([
                'new_name' => 'required|max:255',
                'new_contact' => 'required|max:255|unique:customers,tp',
            ]);

            if ($this->new_credit_details == 1) {
                $this->validate(
                    [
                        'new_credit_limit' => 'required|numeric|min:1|'
                    ],
                    [
                        'new_credit_limit.min' => 'Please enter the credit limit.'
                    ]
                );
            }

            // Insert data
            $data = new CustomerModel();
            $data->customer_name = $this->new_name;
            $data->tp = $this->new_contact;
            $data->is_credit = $this->new_credit_details;
            $data->credit_limit = $this->new_credit_limit;
            // $data->credit_spent = $this->new_credit_spent;
            $data->received_credit = $this->new_received_credit;
            $data->save();


            // Show success message
            session()->flash('message', 'Saved Successfully!');

            // Clear data
            $this->clearData();
        } else {
            // Here update data
            $data = CustomerModel::find($this->key);

            // Validate data for update
            $this->validate([
                'new_name' => 'required|max:255',
                'new_contact' => 'required|max:255|unique:customers,tp,' . $data->id
            ]);

            $data->customer_name = $this->new_name;
            $data->tp = $this->new_contact;
            $data->is_credit = $this->new_credit_details;
            $data->received_credit = $data->received_credit + $this->new_received_credit;

            // Subtract new_received_credit from new_credit_spent
            // $data->credit_spent = $this->new_credit_spent - $this->new_received_credit;

            // Ensure credit_spent is not negative
            // if ($data->credit_spent < 0) {
            //     $data->credit_spent = 0; // Set credit_spent to 0 if it would be negative
            // }
            $data->credit_limit = $this->new_credit_limit;
            $data->save();

            if ($data->is_credit == 1) {
                $new_cash_in = new CashInHistoryModel();
                $new_cash_in->cash_in_type = "Customer Credit";
                $new_cash_in->amount = $this->new_received_credit;
                $new_cash_in->date = date('Y-m-d');
                $new_cash_in->property_id = Auth::user()->property_id;
                $new_cash_in->customer_id = $data->id;
                $new_cash_in->save();
            }
            // Show success message
            session()->flash('message', 'Update Successfully!');
            // Clear data
            $this->clearData();
        }
    }

    //fill box forupdate
    public function updateRecord($id)
    {
        $data = CustomerModel::find($id);
        $this->new_name = $data->customer_name;
        $this->new_contact = $data->tp;
        $this->new_credit_details = $data->is_credit;
        $this->new_credit_limit = $data->credit_limit;
        // $this->new_credit_spent = $data->credit_spent;
        $this->key = $id;

        $this->show_credit_detail = $data->is_credit;
        $this->customer_credit = $data->cridit;
        $this->payable_credit = $data->cridit - $data->received_credit;
        $this->paid_credit = $data->received_credit;

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
        $this->new_credit_details = 0;
        $this->new_received_credit = 0.00;
        $this->new_credit_limit = 0.00;
        // $this->new_credit_spent = 0.00;

        $this->payable_credit = 0.00;
        $this->paid_credit = 0.00;
        $this->show_credit_detail = 0;
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
        return view('livewire.customer')->layout('layouts.master');
    }
}
